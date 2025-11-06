@extends('core::emails.base')

@section('title', 'Félicitations - Candidature Acceptée')

@section('header')
    <h1 style="color: white;">🎉 Félicitations !</h1>
    <p style="margin: 5px 0 0 0; color: white;">Votre candidature a été acceptée</p>
@endsection

@section('content')
    <p>Bonjour <strong>{{ $candidat['nom'] ?? 'Candidat(e)' }}</strong>,</p>
    
    <div style="background: #e8f5e9; padding: 20px; border-left: 4px solid #4CAF50; margin: 20px 0; text-align: center;">
        <p style="margin: 0; font-size: 16pt; color: #2e7d32;">
            <strong>🎓 ADMISSION ACCORDÉE 🎓</strong>
        </p>
    </div>

    <p>Nous avons le plaisir de vous informer que votre candidature pour le programme <strong>{{ $programme ?? '' }}</strong>
    @if(isset($specialite)) en <strong>{{ $specialite }}</strong>@endif 
    a été <strong style="color: #4CAF50;">ACCEPTÉE</strong> pour l'année académique <strong>{{ $anneeAcademique ?? '' }}</strong>.</p>

    <h3>Informations de votre admission :</h3>
    <table style="background: #f5f5f5;">
        <tr>
            <td style="width: 40%; padding: 10px;"><strong>Numéro de dossier :</strong></td>
            <td style="padding: 10px; font-weight: bold; color: #4CAF50;">{{ $numeroDossier ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding: 10px;"><strong>Programme :</strong></td>
            <td style="padding: 10px;">{{ $programme ?? '' }}</td>
        </tr>
        @if(isset($specialite))
        <tr>
            <td style="padding: 10px;"><strong>Spécialité :</strong></td>
            <td style="padding: 10px;">{{ $specialite }}</td>
        </tr>
        @endif
        @if(isset($niveau))
        <tr>
            <td style="padding: 10px;"><strong>Niveau :</strong></td>
            <td style="padding: 10px;">{{ $niveau }}</td>
        </tr>
        @endif
        @if(isset($matricule))
        <tr>
            <td style="padding: 10px;"><strong>Matricule :</strong></td>
            <td style="padding: 10px; font-weight: bold;">{{ $matricule }}</td>
        </tr>
        @endif
    </table>

    @if(isset($dateRentree))
        <div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <p style="margin: 0;"><strong>📅 Date de rentrée :</strong> {{ $dateRentree }}</p>
        </div>
    @endif

    <h3>Pour finaliser votre inscription, veuillez :</h3>
    <ol style="line-height: 2;">
        <li>
            <strong>Confirmer votre admission</strong> en cliquant sur le bouton ci-dessous
            @if(isset($dateConfirmation))
                <br><em style="color: #d32f2f;">Avant le : {{ $dateConfirmation }}</em>
            @endif
        </li>
        <li>
            <strong>Effectuer le paiement des frais d'inscription</strong>
            @if(isset($montantInscription))
                <br>Montant : <strong>{{ $montantInscription }}</strong>
            @endif
        </li>
        <li><strong>Soumettre les pièces justificatives</strong> demandées</li>
        @if(isset($etapesSupplementaires))
            @foreach($etapesSupplementaires as $etape)
                <li>{{ $etape }}</li>
            @endforeach
        @endif
    </ol>

    @if(isset($urlConfirmation))
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $urlConfirmation }}" class="button">Confirmer mon admission</a>
        </div>
    @endif

    @if(isset($documentsAFournir) && count($documentsAFournir) > 0)
        <h3>Documents à fournir :</h3>
        <ul style="background: #f9f9f9; padding: 20px 20px 20px 40px; margin: 10px 0;">
            @foreach($documentsAFournir as $document)
                <li style="margin: 5px 0;">{{ $document }}</li>
            @endforeach
        </ul>
    @endif

    @if(isset($modalitesPaiement))
        <div style="background: #f5f5f5; padding: 15px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>💳 Modalités de paiement :</strong></p>
            {!! $modalitesPaiement !!}
        </div>
    @endif

    @if(isset($avantages) && count($avantages) > 0)
        <h3>En tant qu'étudiant(e) admis(e), vous bénéficiez de :</h3>
        <ul style="line-height: 1.8;">
            @foreach($avantages as $avantage)
                <li>{{ $avantage }}</li>
            @endforeach
        </ul>
    @endif

    <div style="background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0;">
        <p style="margin: 0;"><strong>ℹ️ Information importante :</strong></p>
        <p style="margin: 10px 0 0 0;">
            {{ $informationImportante ?? 'Votre place est réservée jusqu\'à la date de confirmation. Passé ce délai, votre admission pourra être annulée.' }}
        </p>
    </div>

    @if(isset($contact))
        <div style="background: #f5f5f5; padding: 15px; margin: 20px 0;">
            <p style="margin: 0;"><strong>📞 Besoin d\'aide ?</strong></p>
            @if(isset($contact['email']))
                <p style="margin: 5px 0;">Email : {{ $contact['email'] }}</p>
            @endif
            @if(isset($contact['telephone']))
                <p style="margin: 5px 0;">Téléphone : {{ $contact['telephone'] }}</p>
            @endif
            @if(isset($contact['horaires']))
                <p style="margin: 5px 0;">Horaires : {{ $contact['horaires'] }}</p>
            @endif
        </div>
    @endif

    <p style="margin-top: 30px;">Félicitations encore une fois pour votre admission ! Nous sommes impatients de vous accueillir au sein de notre établissement.</p>

    <p>Cordialement,<br><strong>{{ $etablissement ?? 'L\'Équipe Académique' }}</strong></p>
@endsection
