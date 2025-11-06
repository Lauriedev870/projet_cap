@extends('core::pdfs.base')

@section('title', 'Attestation de Réussite - Licence')

@section('header')
    <h1 style="font-size: 20pt;">ATTESTATION DE RÉUSSITE</h1>
    <h2 style="font-size: 14pt; margin-top: 10px;">Diplôme de Licence</h2>
@endsection

@section('content')
    <div style="text-align: justify; line-height: 2; margin-top: 40px;">
        <p style="text-align: center; margin-bottom: 30px;">
            <strong style="font-size: 13pt;">LE DIRECTEUR DES ÉTUDES ATTESTE QUE :</strong>
        </p>

        <div class="info-box" style="margin: 30px 0;">
            <table style="border: none; width: 100%;">
                <tr style="border: none;">
                    <td style="border: none; padding: 8px 0; width: 35%;"><strong>Nom et Prénoms :</strong></td>
                    <td style="border: none; padding: 8px 0;">{{ $etudiant['nom'] ?? '' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none; padding: 8px 0;"><strong>Date de naissance :</strong></td>
                    <td style="border: none; padding: 8px 0;">{{ $etudiant['dateNaissance'] ?? '' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none; padding: 8px 0;"><strong>Lieu de naissance :</strong></td>
                    <td style="border: none; padding: 8px 0;">{{ $etudiant['lieuNaissance'] ?? '' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none; padding: 8px 0;"><strong>Nationalité :</strong></td>
                    <td style="border: none; padding: 8px 0;">{{ $etudiant['nationalite'] ?? '' }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none; padding: 8px 0;"><strong>Matricule :</strong></td>
                    <td style="border: none; padding: 8px 0;">{{ $etudiant['matricule'] ?? '' }}</td>
                </tr>
            </table>
        </div>

        <p style="margin: 30px 0;">
            A satisfait aux examens de <strong>{{ $diplome ?? 'Licence' }}</strong>
            en <strong>{{ $specialite ?? '' }}</strong>, option <strong>{{ $option ?? '' }}</strong>,
            organisés par notre établissement au titre de l'année académique <strong>{{ $anneeAcademique ?? '' }}</strong>.
        </p>

        @if(isset($session))
            <p style="margin: 20px 0;">
                <strong>Session :</strong> {{ $session }}
            </p>
        @endif

        @if(isset($moyenneGenerale))
            <p style="margin: 20px 0;">
                <strong>Moyenne générale obtenue :</strong> {{ number_format($moyenneGenerale, 2) }}/20
            </p>
        @endif

        @if(isset($mention))
            <p style="margin: 20px 0;">
                <strong>Mention :</strong> <span style="font-weight: bold;">{{ $mention }}</span>
            </p>
        @endif

        @if(isset($jury))
            <div style="margin: 30px 0;">
                <p><strong>Délibération du jury du :</strong> {{ $jury['date'] ?? '' }}</p>
                @if(isset($jury['decision']))
                    <p><strong>Décision :</strong> {{ $jury['decision'] }}</p>
                @endif
            </div>
        @endif

        <p style="margin: 30px 0;">
            En foi de quoi, la présente attestation lui est délivrée pour servir et valoir ce que de droit,
            en attendant la délivrance du diplôme définitif.
        </p>

        @if(isset($note))
            <div style="background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin: 20px 0;">
                <p style="margin: 0; font-size: 10pt;"><strong>Note :</strong> {{ $note }}</p>
            </div>
        @endif
    </div>

    <div style="margin-top: 60px;">
        <table style="border: none; width: 100%;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; text-align: left;">
                    <p>Fait à {{ $lieu ?? '___________' }},</p>
                    <p>Le {{ $date ?? now()->format('d/m/Y') }}</p>
                </td>
                <td style="border: none; width: 50%; text-align: center;">
                    <p><strong>Le Directeur des Études</strong></p>
                    <p style="margin-top: 60px;">Signature et Cachet</p>
                </td>
            </tr>
        </table>
    </div>
@endsection
