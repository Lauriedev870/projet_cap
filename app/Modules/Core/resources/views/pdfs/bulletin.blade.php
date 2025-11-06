@extends('core::pdfs.epac-base')

@section('title', 'Bulletin')

@section('body-font-size', '12px')
@section('body-font-weight', 'normal')

@section('logo-epac-ext', 'jpeg')
@section('logo-cap-ext', 'jpeg')

@section('header-h2-size', '11px')
@section('header-h3-top', '-20px')
@section('header-p-top', '-35px')
@section('header-hr-top', '-45px')

@section('logo-epac-styles')
    width: 120px;
    top: 5px;
@endsection

@section('logo-cap-styles')
    width: 110px;
    top: 5px;
@endsection

@section('hide-annee', true)

@section('document-title')
    {{-- Titre personnalisé dans le content --}}
@endsection

@section('extra-styles')
    .main {
        text-align: center;
        margin-block: 50px;
        position: relative;
        top: -35px;
        font-weight: bolder;
    }
    .corps {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        text-align: center; 
        font-size: 12px; 
    }
    .corps th,
    .corps td {
        border: 1px solid #ccc;
        padding: 4px;
        text-align: left;
    }
@endsection

@section('content')
<div class="main">
    <div style="text-align: center; font-weight: bold; margin-bottom: 7px; font-size: 25px;">BULLETIN DE NOTES</div>
    <div style="text-align: center; font-weight: bold; margin-bottom: 20px; font-size: 15px;">Année Académique: {{ $annee }}</div>
    <div style="position: absolute; top: -5px; right: 0px;">
        <img src="data:image/png;base64,{{ $qrcode }}" width="80px" height="80px" class="qrcode" alt="Code QR">
    </div>
    
    <table style="width: 100%; text-align: left; margin-bottom: 10px; font-size: 11px; border: 0px; margin-top: 10px;">
        <tbody>
            <tr>
                <td><span style="font-weight: normal;">Matricule :</span> <span style="font-weight: bolder;"> {{ $etudiant->matricule }} </span></td>
                <td><span style="font-weight: normal;">Sexe :</span> <span style="font-weight: bolder;"> {{ ucfirst($etudiant->genre) }} </span></td>
                <td><span style="font-weight: normal;">Cycle :</span> <span style="font-weight: bolder;"> {{ $etudiant->filiere?->diplome?->nom }} </span></td>
            </tr>
            <tr>
                <td><span style="font-weight: normal;">Nom :</span> <span style="font-weight: bolder;"> {{ $etudiant->nom }} </span></td>
                <td><span style="font-weight: normal;">Date de naissance :</span> <span style="font-weight: bolder;"> {{ translateEnglishDateToFrench($etudiant->date_naissance->format("d/m/Y")) }} </span></td>
                <td><span style="font-weight: normal;">Filière :</span><span style="font-weight: bolder;"> {{ $etudiant->filiere?->nom }} </span></td>
            </tr>
            <tr>
                <td><span style="font-weight: normal;">Prénoms :</span> <span style="font-weight: bolder;"> {{ $etudiant->prenoms }} </span></td>
                <td><span style="font-weight: normal;">Lieu de naissance : </span> <span style="font-weight: bolder;"> {{ $etudiant->lieu_de_naissance }}</span></td>
                <td><span style="font-weight: normal;">Niveau : </span> <span style="font-weight: bolder;"> Classes Préparatoires </span></td>
            </tr>
        </tbody>
    </table>
    
    <table class="corps">
        <thead style="font-weight: bold;">
            <tr>
                <td>N°</td>
                <td>Codes</td>
                <td>Unités d'Enseignements</td>
                <td>Crédits</td>
                <td>Moyenne /100</td>
                <td>Freq*</td>
                <td>Etat UE</td>
            </tr>
        </thead>
        <tbody style="width: 100%; font-weight: bold;">
            <tr>
                <td colspan="7" style="font-weight: bold; text-align: center;"></td>
            </tr>
            @php
            $num = 1;
            @endphp
            @foreach($bulletin_data[0] as $line)
            @if(is_array($line))
            <tr>
                <td>{{ $num }}</td>
                <td>{{ $line["code"] }}</td>
                <td>{{ $line['nom'] }}</td>
                <td>{{ $line['credit'] }}</td>
                <td>{{ $line['moyenne'] * 5 }}</td>  
                <td>{{ $line['frequence'] }}</td>
                <td>Validé</td>
                @php
                    $num++;
                @endphp
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%; margin: 7px; text-align: center; font-size: 13px; border: 0px;">
        <tbody>
            <tr>
                <td colspan="3" style="font-weight: bolder; text-align: center;">BILAN DE L'ANNÉE</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%; text-align: left; padding-left: 10px; margin-bottom: 10px; font-size: 12px; border: 0px;">
        <tbody>
            <tr>
                <td><span style="font-weight: normal;">Nombre de UE validé : </span> {{ $bulletin_data[0]["nombre_ue_valide"] }}/{{ $bulletin_data[0]["nombre_ue"] }}</td>
                <td><span style="font-weight: normal;">Crédits obtenus : </span> {{ $bulletin_data[0]["nombre_credit_obtenu"] }}/{{ $bulletin_data[0]["nombre_credit_total"] }}</td>
                <td><span style="font-weight: normal;">Moyenne : </span> {{ $bulletin_data[0]["moyenne"] }}</td>
            </tr>
            <tr>
                <td><span style="font-weight: normal;">Nombre de UE cumulé : </span> {{ $bulletin_data[0]["nombre_ue_valide"] }}/{{ $bulletin_data[0]["nombre_ue"] }}</td>
                <td><span style="font-weight: normal;">Total crédits cumulés : </span> {{ $bulletin_data[0]["nombre_credit_obtenu"] }}/{{ $bulletin_data[0]["nombre_credit_total"] }}</td>
                <td><span style="font-weight: normal;">Grade ETCS : </span>{{ $bulletin_data[0]["grade"] }}</td> 
            </tr>
            <tr>
                <td><span style="font-weight: normal;">% Crédits requis : </span> 80%</td>
                <td><span style="font-weight: normal;">% Crédits cumulés : </span> {{ round((float)($bulletin_data[0]["nombre_credit_obtenu"] *100 )/ (float)($bulletin_data[0]["nombre_credit_total"]), 2) }}%</td>
                <td><span style="font-weight: normal;">Décision du conseil : </span> {{ $bulletin_data[0]["decision"] }}</td>
            </tr>
        </tbody>
    </table>
    
    <div style="width: 100%; text-align: center;">
        <h5>Fait à Abomey-Calavi le {{ translateEnglishDateToFrench(now()->format("d/m/Y")) }}</h5>
        <h5>Le Chef CAP</h5>
        <div style="height: 50px;"></div>
        <h5 style="text-decoration: underline;">{{ $signataire->nomination }}</h5>
    </div>
</div>
@endsection

@section('footer-text')
    <span><i>*Nombre de composition dans l'UE</i></span><br>
    <span style="font-weight: bold;">NB: </span> Ce relevé ne peut en aucun cas tenir lieu d'attestation de diplôme et n'est délivré qu'une seule fois
@endsection
