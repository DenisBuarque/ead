<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boletim Histórico Acadêmico</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        h3,
        p {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        table {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table th,
        tr,
        td {
            border: #000000 1px solid;
        }

        .note {
            text-align: center;
        }

    </style>
</head>

<body>
    <h3>Seminário Teológico Batista de Alagoas</h3>
    <p>Rua Aristeu de Andrade, Farol, Maceió, AL (82) 98863-7495</p>

    <table cellspacing="0">
        <thead>
            <tr>
                <th>Disciplina</th>
                @if ($course->institution == 'setbal')
                    <th>Ano</th>
                    <th>Semestre</th>
                    <th>Créditos</th>
                    <th>CH</th>
                    <th>Nota</th>
                @endif
                @if ($course->institution == 'ead')
                    <th>1º nota</th>
                    <th>2º nota</th>
                    <th>3º nota</th>
                    <th>Média</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($disciplines->sortBy('year') as $discipline)
                @php
                    $inscription = App\Http\Controllers\StudentController::verify_inscription($student->id, $discipline->course_id, $discipline->id);
                    $expiration_date = App\Http\Controllers\StudentController::expiration_date($student->id, $discipline->course_id, $discipline->id);
                @endphp
                <tr>
                    <td>
                        @if ($inscription)
                            <strong>{{ $discipline->title }}</strong>
                        @else
                            <span class="font-weight-light">{{ $discipline->title }}</span>
                        @endif
                    </td>
                    @if ($course->institution == 'setbal')
                        <td class="note">{{ $discipline->year }}º</td>
                        <td class="note">{{ $discipline->semester }}º</td>
                        <td class="note">{{ $discipline->credits }}</td>
                        <td class="note">{{ $discipline->workload }}</td>
                        <td class="note">{{ number_format(App\Http\Controllers\StudentController::getNote($discipline->course_id, $discipline->id, $student->id),1,'.','.') }}</td>
                    @endif
                    @if ($course->institution == 'ead')
                        <td class="note">
                            @php
                                $note1 = App\Http\Controllers\DashboardController::getNoteMultiple($discipline->course->id, $discipline->id, $student->id);
                            @endphp
                            @if ($note1 == 0)
                                <s>0.0</s>
                            @else
                                {{ number_format($note1, 1, '.', '') }}
                            @endif
                        </td>
                        <td class="note">
                            @php
                                $note2 = App\Http\Controllers\DashboardController::getNoteOpen($discipline->course->id, $discipline->id, $student->id);
                            @endphp
                            @if ($note2 == 0)
                                <s>0.0</s>
                            @else
                                {{ number_format($note2, 1, '.', '') }}
                            @endif
                        </td>
                        <td class="note">
                            @php
                                $note3 = App\Http\Controllers\DashboardController::getNoteJob($discipline->course->id, $discipline->id, $student->id);
                            @endphp
                            @if ($note3 == 0)
                                <s>0.0</s>
                            @else
                                {{ number_format($note3, 1, '.', '') }}
                            @endif
                        </td>
                        <td class="note">
                            @php
                                if ($note1 == 0 && $note2 == 0 && $note3 == 0) {
                                    echo '<s>0.0</s>';
                                } else {
                                    if ($note1 != 0 && $note2 != 0 && $note3 != 0) {
                                        $soma_notas = $note1 + $note2 + $note3;
                                
                                        $media = $soma_notas / 3;
                                        if ($media == 0) {
                                            echo '0.0';
                                        } elseif ($media > 0 && $media <= 6.9) {
                                            echo number_format($media, 1, '.', '');
                                        } else {
                                            echo number_format($media, 1, '.', '');
                                        }
                                    } else {
                                        echo '...';
                                    }
                                }
                            @endphp
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>
