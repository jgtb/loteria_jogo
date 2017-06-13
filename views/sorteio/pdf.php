<?php
include("../vendor/mpdf/mpdf/mpdf.php");

$mpdf = new mPDF('utf-8', 'A4-L', '', 'timesnewroman', 5, 5, 5, 5, '', 'P');

$css = file_get_contents('../web/css/pdf.css');
$mpdf->WriteHTML($css, 1);

$mpdf->SetTitle($model->categoria->descricao);

$html = '<h3>' . $model->categoria->descricao . '</h3>';
$mpdf->WriteHTML($html);

$html = '<h4 class="first">Data do Sorteio: ' . date('d/m/Y', strtotime($model->data)) . '</h4>';
$mpdf->WriteHTML($html);

$html = '<h4>Número do Sorteio: ' . $model->numero . '</h4>';
$mpdf->WriteHTML($html);

$html = '<h4>Número de Jogos: ' . count($model->jogos) . '</h4>';
$mpdf->WriteHTML($html);

$html = '<table class="table table-bordered table-striped table-condensed table-responsive">
        <thead>
        <tr>
        <th>#</th>';

$model->categoria_id == 6 ? $html .= '<th>Time</th>' : '';

$html .= 
      '<th>Números</th>
      </tr>
      </thead>
      <tbody>';

$modelsJogos = $model->jogos;
foreach ($modelsJogos as $index => $modelJogo) {
    $modelsNumero = $modelJogo->numeros;
    $numeros = '';
    foreach ($modelsNumero as $modelNumero) {
        $numeros .= '<span>' . $modelNumero->numero . ' </span>';
    }
    $html .= '<tr>';
    $html .= '<td>' . ($index + 1) . '</td>';
    $model->categoria_id == 6 ? $html .= '<td>' . $modelJogo->jogoTime->time->descricao . '</td>' : '';
    $html .= '<td>' . $numeros . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

$mpdf->WriteHTML($html);

$mpdf->Output();
exit;
