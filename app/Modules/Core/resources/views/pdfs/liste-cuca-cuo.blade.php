<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste CUCA-CUO</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4 landscape;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4CAF50;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 20pt;
        }
        .header .subtitle {
            color: #666;
            font-size: 10pt;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 8pt;
        }
        table th, table td {
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background: #f8f8f8;
            font-weight: bold;
            color: #333;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .info-box {
            background: #f5f5f5;
            padding: 10px;
            border-left: 4px solid #4CAF50;
            margin: 15px 0;
            font-size: 9pt;
        }
        .info-box p {
            margin: 3px 0;
        }
        .footer-signature {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LISTE CUCA-CUO</h1>
        <div class="subtitle">{{ $anneeAcademique ?? '' }}</div>
    </div>

    <div class="info-box">
        @if(isset($classe))
            <p><strong>Classe :</strong> {{ $classe }}</p>
        @endif
        @if(isset($specialite))
            <p><strong>Spécialité :</strong> {{ $specialite }}</p>
        @endif
        @if(isset($niveau))
            <p><strong>Niveau :</strong> {{ $niveau }}</p>
        @endif
        @if(isset($dateGeneration))
            <p><strong>Date de génération :</strong> {{ $dateGeneration }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">N°</th>
                <th style="width: 10%;">Matricule</th>
                <th style="width: 18%;">Nom et Prénoms</th>
                <th style="width: 8%;">Date Nais.</th>
                <th style="width: 12%;">Lieu Nais.</th>
                <th style="width: 6%;">Sexe</th>
                <th style="width: 10%;">Nationalité</th>
                <th style="width: 13%;">Diplôme</th>
                <th style="width: 20%;">Établ. Origine</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $index => $etudiant)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $etudiant['matricule'] ?? $etudiant['student_id'] ?? '' }}</td>
                    <td>{{ $etudiant['nom'] ?? $etudiant['name'] ?? '' }}</td>
                    <td style="text-align: center;">{{ $etudiant['dateNaissance'] ?? '' }}</td>
                    <td>{{ $etudiant['lieuNaissance'] ?? '' }}</td>
                    <td style="text-align: center;">{{ $etudiant['sexe'] ?? '' }}</td>
                    <td>{{ $etudiant['nationalite'] ?? '' }}</td>
                    <td>{{ $etudiant['diplome'] ?? '' }}</td>
                    <td>{{ $etudiant['etablissementOrigine'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <p><strong>Nombre total d'étudiants :</strong> {{ count($etudiants) }}</p>
        @if(isset($statistiques))
            <p><strong>Garçons :</strong> {{ $statistiques['garcons'] ?? '' }} | <strong>Filles :</strong> {{ $statistiques['filles'] ?? '' }}</p>
        @endif
    </div>

    <div class="footer-signature">
        <p>Fait à {{ $lieu ?? '___________' }}, le {{ $date ?? now()->format('d/m/Y') }}</p>
        <p style="margin-top: 40px;"><strong>Le Directeur des Études</strong></p>
        <p style="margin-top: 50px;">Signature et Cachet</p>
    </div>
</body>
</html>
