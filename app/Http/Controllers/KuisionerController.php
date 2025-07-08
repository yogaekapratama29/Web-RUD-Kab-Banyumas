<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Respondent;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KuisionerController extends Controller
{
    public function index()
    {
        $questions = Question::orderBy('kategori')->get();
        return view('form', compact('questions'));
    }

    public function submit(Request $req)
    {
        $req->validate([
            'nama'      => 'required|string',
            'usia'      => 'required|numeric',
            'desa'      => 'required|string',
            'latitude'  => 'required',
            'longitude' => 'required',
            'jawaban'   => 'required|array',
        ]);

        $resp = Respondent::create([
            'nama'      => $req->nama,
            'usia'      => $req->usia,
            'desa'      => $req->desa,
            'latitude'  => $req->latitude,
            'longitude' => $req->longitude,
        ]);

        foreach ($req->jawaban as $qId => $val) {
            Answer::create([
                'respondent_id' => $resp->id,
                'question_id'   => $qId,
                'jawaban'       => $val,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Terima kasih telah mengisi survei!');
    }

    public function dashboard()
    {
        $koordinat = Respondent::select('nama', 'latitude', 'longitude')
            ->whereNotNull('latitude')
            ->get();

        $categories = Question::select('kategori')->distinct()->pluck('kategori');

        $pieCharts = [];
        $pieLabels = [];

        $labelMap = [
            1 => 'Sangat Tidak Puas',
            2 => 'Tidak Puas',
            3 => 'Cukup Puas',
            4 => 'Puas',
            5 => 'Sangat Puas'
        ];

        foreach ($categories as $category) {
            $questions = Question::where('kategori', $category)->get();

            foreach ($questions as $q) {
                $chartData = Answer::where('question_id', $q->id)
                    ->select('jawaban', DB::raw('COUNT(*) as jumlah'))
                    ->groupBy('jawaban')->orderBy('jawaban')->get()
                    ->map(function ($item) use ($labelMap) {
                        $item->jawaban = $labelMap[$item->jawaban] ?? 'Tidak Diketahui';
                        return $item;
                    });

                $pieCharts[] = $chartData;
                $pieLabels[] = $q->pertanyaan;
            }
        }

        $bar = Answer::select('questions.kategori AS label', DB::raw('COUNT(*) AS total'))
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->groupBy('questions.kategori')
            ->orderBy('questions.kategori')
            ->get();

        $barLabels = $bar->pluck('label');
        $barValues = $bar->pluck('total');

        $summary = DB::table('respondents')
            ->selectRaw('
                desa AS kecamatan,
                COUNT(respondents.id) AS responden,
                MAX(respondents.created_at) AS pa_raw,
                MAX(respondents.updated_at) AS dja_raw,
                AVG(answers.jawaban) AS rata2
            ')
            ->leftJoin('answers', 'respondents.id', '=', 'answers.respondent_id')
            ->groupBy('desa')->orderBy('desa')->get()
            ->map(function ($row) {
                $row->pa  = $row->pa_raw  ? Carbon::parse($row->pa_raw)->format('d-m') : '-';
                $row->dja = $row->dja_raw ? Carbon::parse($row->dja_raw)->format('d-m') : '-';
                unset($row->pa_raw, $row->dja_raw);
                return $row;
            });

        $totalResp = Respondent::count();
        $avgScore  = Answer::avg('jawaban');
        $target    = 200;

        $metrics = [
            'avg_score'  => number_format($avgScore, 2),
            'total_resp' => $totalResp,
            'progress'   => $target ? round($totalResp / $target * 100) : 0,
        ];

        return view('dashboard', [
            'koordinat'   => $koordinat,
            'pieCharts'   => $pieCharts,
            'pieLabels'   => $pieLabels,
            'barLabels'   => $barLabels,
            'barValues'   => $barValues,
            'summary'     => $summary,
            'metrics'     => $metrics,
        ]);
    }

    public function form()
    {
        $kategoriList = Question::select('kategori')->distinct()->pluck('kategori');
        return view('form-kategori', compact('kategoriList'));
    }

    public function formByKategori($kategori)
    {
        $questions = Question::where('kategori', $kategori)->get();
        return view('form-pertanyaan', [
            'kategori' => $kategori,
            'questions' => $questions
        ]);
    }

    public function pilihKategori()
    {
        $kategoriList = Question::select('kategori')->distinct()->pluck('kategori');
        return view('pilih-kategori', compact('kategoriList'));
    }
}
