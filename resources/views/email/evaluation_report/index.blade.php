<center><img src="{{ asset('images/logo_eg.jpg') }}" width="200" height="70" alt="logo"></center>
<p>Cher client,</p>
<p>
    Nous vous prions de bien vouloir recevoir en pièce jointe votre rapport d'évaluation du véhicule immatriculé
    <b>{{ $calculation->license_plate ?? '' }}</b>.
</p>
<p>
    RÉFÉRENCE : <b>{{ $calculation->reference ?? '' }}</b><br>
    IMMATRICULATION : <b>{{ $calculation->license_plate ?? '' }}</b><br>
    MARQUE : <b>{{ $calculation->vehicleCharacteristic?->vehicleBrand?->label ?? '' }}</b><br>
    MODÈLE : <b>{{ $calculation->vehicleCharacteristic?->vehicleModel?->label ?? '' }}</b><br>
    TYPE : <b>{{ $calculation->vehicleCharacteristic?->vehicleType?->label ?? '' }}</b><br>
    OPTION : <b>{{ $calculation->vehicleCharacteristic?->vehicleOption?->label ?? '' }}</b><br>
    COULEUR : <b>{{ $calculation->vehicleCharacteristic?->vehicleColor?->label ?? '' }}</b><br>
    KILOMETRAGE : <b>{{ $calculation->vehicleCharacteristic?->vehicleMileage ?? '' }}</b><br>
    ÉNERGIE : <b>{{ $calculation->vehicleCharacteristic?->vehicleEnergy?->label ?? '' }}</b><br>
    GENRE : <b>{{ $calculation->vehicleCharacteristic?->vehicleGenre?->label ?? '' }}</b><br>
    USAGE : <b>{{ $calculation->vehicleCharacteristic?->vehicleUsage?->label ?? '' }}</b><br>
    EQUIPEMENT : <b>{{ $calculation->vehicleCharacteristic?->equipment ?? '' }}</b><br>
    OPTIONS : <b>{{ $calculation->vehicleCharacteristic?->options ?? '' }}</b><br>
    ASSURÉ : <b>{{ $calculation->insured ?? '' }}</b><br>
    VALEUR NEUVE : <b>{{ number_format($calculation->vehicle_new_value ?? 0, 0, ',', ' ') }} FCFA</b><br>
    DÉPRÉCIATION : <b>{{ number_format($calculation->depreciation_rate ?? 0, 2, ',', ' ') }} %</b><br>
    PLUS-VALUE KILOMÉTRIQUE : <b>{{ number_format($calculation->kilometric_incidence ?? 0, 0, ',', ' ') }} FCFA</b><br>
    PLUS-VALUE INCIDENCE MARCHÉ : <b>{{ number_format($calculation->market_incidence ?? 0, 0, ',', ' ') }} FCFA</b><br>
    PLUS-VALUE INCIDENCE KILOMÉTRIQUE : <b>{{ number_format($calculation->kilometric_incidence ?? 0, 0, ',', ' ') }} FCFA</b><br>
    VALEUR VENALE HORS INCIDENCES : <b>{{ number_format($calculation->theorical_vehicle_market_value ?? 0, 0, ',', ' ') }} FCFA</b><br>
    VALEUR VENALE AVEC INCIDENCES : <b>{{ number_format($calculation->vehicle_market_value ?? 0, 0, ',', ' ') }} FCFA</b><br>

</p>
<p>VALORIX vous remercie.</p>
