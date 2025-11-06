@extends('core::emails.base')

@section('title', 'Décision de Candidature')

@section('header')
    <h1>Réponse à votre Candidature</h1>
@endsection

@section('content')
    <p>Bonjour <strong>{{ $candidat['nom'] ?? 'Candidat(e)' }}</strong>,</p>
    
    <p>Nous vous remercions sincèrement pour l'intérêt que vous portez à notre établissement et pour avoir soumis votre candidature au programme <strong>{{ $programme ?? '' }}</strong>
    @if(isset($specialite)) en <strong>{{ $specialite }}</strong>@endif 
    pour l'année académique <strong>{{ $anneeAcademique ?? '' }}</strong>.</p>

    <p>Après un examen attentif de votre dossier par notre commission d'admission, nous avons le regret de vous informer que nous ne sommes pas en mesure de vous proposer une place pour cette rentrée.</p>

    <div style="background: #f5f5f5; padding: 15px; margin: 20px 0;">
        <p style="margin: 0;"><strong>Numéro de dossier :</strong> {{ $numeroDossier ?? '' }}</p>
        <p style="margin: 5px 0 0 0;"><strong>Statut :</strong> Non retenu(e)</p>
    </div>

    @if(isset($motifRejet))
        <div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>Informations complémentaires :</strong></p>
            <p style="margin: 0;">{{ $motifRejet }}</p>
        </div>
    @endif

    @if(isset($suggestions) && count($suggestions) > 0)
        <h3>Nous vous suggérons :</h3>
        <ul style="line-height: 1.8;">
            @foreach($suggestions as $suggestion)
                <li>{{ $suggestion }}</li>
            @endforeach
        </ul>
    @endif

    @if(isset($programmesAlternatifs) && count($programmesAlternatifs) > 0)
        <div style="background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>💡 Programmes alternatifs disponibles :</strong></p>
            <ul style="margin: 0 0 0 20px; padding: 0;">
                @foreach($programmesAlternatifs as $prog)
                    <li style="margin: 5px 0;">{{ $prog }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($possibiliteRecandidature))
        <p>{{ $possibiliteRecandidature }}</p>
    @else
        <p>Nous vous encourageons à soumettre une nouvelle candidature pour la prochaine rentrée académique, en tenant compte des recommandations que nous pourrions vous avoir communiquées.</p>
    @endif

    @if(isset($urlInfos))
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $urlInfos }}" class="button">Plus d'informations</a>
        </div>
    @endif

    @if(isset($contact))
        <div style="background: #f5f5f5; padding: 15px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>📞 Contact :</strong></p>
            <p style="margin: 0;">Si vous souhaitez obtenir plus d'informations sur votre candidature :</p>
            @if(isset($contact['email']))
                <p style="margin: 5px 0;">Email : {{ $contact['email'] }}</p>
            @endif
            @if(isset($contact['telephone']))
                <p style="margin: 5px 0;">Téléphone : {{ $contact['telephone'] }}</p>
            @endif
        </div>
    @endif

    <p style="margin-top: 30px;">Nous apprécions l'effort que vous avez consacré à votre candidature et vous souhaitons beaucoup de succès dans la poursuite de vos projets académiques et professionnels.</p>

    <p>Cordialement,<br><strong>{{ $etablissement ?? 'La Commission d\'Admission' }}</strong></p>
@endsection
