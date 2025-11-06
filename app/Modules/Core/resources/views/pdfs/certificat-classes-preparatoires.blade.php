@extends('core::pdfs.epac-base')

@section('title', 'Attestation PDF')

@section('body-font-size', '13pt')
@section('body-font-weight', 'normal')
@section('body-margin', '5cm 2.5cm 2cm 2.5cm')

@section('font-faces')
    @font-face {
        font-family: "ALGERIA";
        src: url({{ storage_path('fonts/ALGERIA.ttf') }});
    }
    @font-face {
        font-family: "Arial";
        src: url({{ storage_path('fonts/arial.ttf') }});
    }
    @font-face {
        font-family: "Albertus Medium";
        src: url({{ storage_path('fonts/albr55w.ttf') }});
    }
    @font-face {
        font-family: 'Pristina';
        src: url({{ storage_path('fonts/PRISTINA.ttf') }}) format('truetype');
    }
    @font-face {
        font-family: "Berlin Sans FB";
        src: url({{ storage_path('fonts/Berlin Sans FB Regular.ttf') }});
    }
@endsection

@section('extra-styles')
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        text-align: center;
        font-family: "Albertus Medium";
    }
    .top {
        height: 2.75cm;
        border-radius: 1px solid black;
        text-align: left;
    }
    .attestation {
        font-size: 35pt;
        font-family: 'ALGERIA';
    }
    .diplome {
        font-size: 10pt;
        font-weight: bold;
        margin-bottom: 13px;
        letter-spacing: -1px;
    }
    .main {
        text-align: justify;
        margin-top: 20px;
    }
    .first {
        margin-bottom: 9px;
        font-style: italic;
        font-size: 13pt;
    }
    .mention {
        text-decoration: underline;
        font-weight: normal;
    }
    .retrait {
        margin-left: 10mm;
    }
    .info {
        margin-top: 5px;
    }
    .info p {
        margin-top: 2px;
        margin-bottom: 2px;
    }
    .filiere {
        text-transform: uppercase;
        color: rgba(36, 88, 187, 1);
        font-size: 16pt;
        font-family: 'Berlin Sans FB';
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: center;
    }
    .text {
        margin-bottom: 4px;
    }
    .avant {
        font-family: 'Berlin Sans FB';
    }
    .decision {
        color: blue;
        opacity: .8;
        font-style: normal;
        font-size: 13pt;
    }
    .directeur {
        margin-top: 18px;
        text-align: center;
        padding-left: 42%;
    }
    .name {
        font-size: 13pt;
        font-style: italic;
    }
    .paragraph-pristina {
        font-size: 14pt;
        font-family: 'Pristina';
        letter-spacing: 1.3px;
    }
@endsection

@section('custom-header', true)

@section('custom-header')
<div style="width: 100vw;height: 100vh;">
    <div>
        <div class="top">
            <div style="margin-top: 1.21cm; font-weight: normal; font-size: 12pt; margin-left: 3.4cm; font-family: 'Arial'; display: none;">
                CAP / DA /
            </div>
        </div>
        <div class="bottom">
            <p class="attestation">CERTIFICAT PREPARATOIRE <br> AUX ETUDES D'INGENIEUR</p>
            <p><img src="{{ storage_path('banner.png') }}" alt="" srcset=""></p>
        </div>
    </div>
</div>
@endsection

@section('hide-footer', true)

@section('content')
<div class="main">
    <p class="paragraph-pristina">
        <span class="retrait">Le</span> Directeur de l'Ecole Polytechnique d'Abomey-Calavi
        (EPAC), ex-Collège Polytechnique Universitaire (CPU), soussigné, atteste que:
    </p>
    
    <div class="info">
        <p>
            <span>{{ $etudiant->genre == 'masculin' ? 'Mr' : 'Mlle' }} </span>
            <span style="text-transform: uppercase;">{{ $etudiant->nom }}</span>
            <span style="text-transform: capitalize;"> {{ $etudiant->prenoms }}</span>
        </p>
        <p>
            Né<span>{{ $etudiant->genre == 'Masculin' ? '' : 'e' }}</span>
            {{ $etudiant->ne_vers == 0 ? 'le ' : '' }}<span class="date">{{ $etudiant->date_naissance }}</span> à
            <span class="lieu" style="text-transform:capitalize;">{{ $etudiant->lieu_naissance }} (Rep. {{ $etudiant->pays_naissance }})</span>
        </p>
        <p>
            @if(trim(str_replace("-","",$etudiant->matricule))) Numéro matricule : {{ $etudiant->matricule }} @endif
            a obtenu le Certificat Préparatoire aux Etudes d'Ingénieur conformément à la délibération du
            <span>{{ $etudiant->filiere->diplome->libelle }}</span> le {{ $etudiant->date_soutenance }}:
        </p>
    </div>
    
    <div class="filiere">{{ $etudiant->filiere->libelle }}</div>
    
    <div class="paragraph-pristina" style="margin-top: 15px;">
        <span class="retrait">La</span> présente attestation, revêtue du sceau de l'EPAC, est définitive et est délivrée pour servir et valoir ce que de droit, en attendant l'établissement du Diplôme.
    </div>
    
    <div class="directeur">
        <p class="first" style="text-transform: capitalize;">{{ $signataire->poste }},</p>
        <div style="height: 55px;"></div>
        <p style="text-decoration: underline;">
            <strong class="name">{{ $signataire->nomination }}</strong>
        </p>
    </div>
</div>
@endsection
