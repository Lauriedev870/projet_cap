@extends('core::pdfs.epac-base')

@section('title', 'Fiche récapitulatif')

@section('document-title')
    FICHE RECAPITULATIF DE NOTES
@endsection

@section('info-table')
<table class="info-table">
    <tr>
    </tr>
    <tr>
        <td>Filière : <strong>{{ $filiere }}</strong></td>
        <td>Période :  ....................</td>
    </tr>
    <tr>
        <td>
            Classe : 
            @if($classe == 'DIC-GE')
                <strong>GE-1</strong>
            @elseif($classe == 'DIC-GC')
                <strong>GC-1</strong>
            @elseif($classe == 'DIC-GT')
                <strong>GT-1</strong>
            @endif
        </td>
        <td></td>
    </tr>
    <tr>
        <td>Matière :  ..................................................................................</td>
        <td></td>
    </tr>
    <tr>
        <td>Enseignant : .........................................................................................</td>
        <td></td>
    </tr>
</table>
@endsection

@section('content')
<table>
    <thead>
        <tr>
            <th style="text-align:center">N°</th>
            <th style="text-align:center">Matricule</th>
            <th style="width:250px;overflow:hidden;text-align:center">Noms et Prénoms</th>
            <th style="text-align:center">Red</th>
            <th style="text-align:center">Note1 /20</th>
            <th style="text-align:center">Note2 /20</th>
            <th style="text-align:center">Note3 /20</th>
            <th style="text-align:center">Moy/20</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($etudiants as $key => $etudiant)
            <tr>
                <td style="text-align:center">{{ $loop->iteration }}</td>
                <td style="text-align:center">{{  $etudiant->etudiant->matricule }}</td>
                <td>{{ $etudiant->etudiant->nom . " " . $etudiant->etudiant->prenoms }}</td>
                <td style="text-align:center">
                    @if(in_array($etudiant->etudiant->nom, ['BIAOU', 'HOUNSOUNOU', 'GBADAMASSI']))
                        R
                    @endif
                </td>
                <td style="text-align:center"></td>
                <td style="text-align:center"></td>
                <td style="text-align:center"></td>
                <td style="text-align:center"></td>
            </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center">
                Aucun étudiant
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
