<!DOCTYPE html>
<html lang="str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Rapport d'évaluation {{$calculation->reference}} / {{$calculation?->entity?->name ?? ''}}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <link href="asset('css/app.css') }}" rel="stylesheet">
        <script src="asset('js/app.js') }}" defer></script>



        <style>



            <?php include(public_path().'/bootstrap/css/bootstrap.css');?>

            table, caption, th, td {
                border: 0px solid;
                font-size: 12px;
                padding: 2px;
            }

            body{
                font-family: "Times New Roman", Times, serif;
                font-size: 12px;
                margin-top: 1cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 3cm;
            }

            .watermark {
                position: absolute;
                opacity: 0.12;
                font-size: 75px;
                width: 100%;
                z-index: 100;
                transform: rotate(-45deg);
                text-align: center;
            }

            @page {
                margin: 0cm 0cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                text-align: center;
                line-height: 1.5cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed;
                bottom: 0.5cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;
                margin-left: 1cm;
                margin-right: 1cm;
                font-size: 9px;

                /** Extra personal styles **/
                text-align: center;
            }

            .relative {
                position: relative;
            }

            .left {
                position: absolute;
                left: 0;
            }

            .center {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }

            .right {
                position: absolute;
                right: 0;
            }

        </style>
    </head>
    <body class="antialiased">
        <header>
            
        </header>
        <footer>
            <hr style="border: 1px solid black;">
            <b>{{$calculation?->entity?->footer_description ?? ''}}</b><br>
        </footer>

        <table class="table text-center">
            <thead style="border: 1px solid; font-size: 12px;">
            <tr style="border: 1px solid; font-size: 12px;">
                <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                    <img src="{{$logo}}" alt="logo" style="text-align: center; width:170px; height:100px;">
                </th>
                <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">ESTIMATION DE VALEUR VENALE <span class="text-danger">N° {{$calculation->reference ?? ''}}</span></th>
                <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                    <img src="{{$qr_code}}" alt="qr_code" style="text-align: center; width:100px; height:100px;">
                    <br>
                    DATE: {{ \Carbon\Carbon::parse($calculation?->created_at)->format('d/m/Y') }}

                </th>
            </tr>
            </thead>
        </table>

        <table class="table text-center" style="border-spacing: 0px;">
            <thead style="border: 1px solid; font-size: 12px; border-spacing: 0px;">
                <tr style="border: 1px solid; font-size: 12px; background-color: rgb(223, 221, 218);">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;" colspan="2">INFORMATIONS</span></th>
                </tr>
            </thead>
            <thead style="border: 1px solid; font-size: 12px; border-spacing: 0px;">
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Immatriculation Vehicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->license_plate ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        N° Serie Vehicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->serial_number ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                    Marque du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleModel?->brand?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Modèle du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleModel?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Nom commercial du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleModel?->description ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Genre du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleGenreUsage?->vehicleGenre?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Usage du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleGenreUsage?->usage?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Energie du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleEnergy?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Puissance fiscale du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{number_format($calculation?->vehicleCharacteristic?->fiscal_power ?? 0, 0, ',', ' ')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Nombre de places du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{number_format($calculation?->vehicleCharacteristic?->nb_seats ?? 0, 0, ',', ' ')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Type de véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->type ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Options du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>
                            <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->options ?? '')}}</b>
                        </b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Date de première mise en circulation du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        @if($calculation?->first_entry_into_circulation_date)
                            <b>{{ \Carbon\Carbon::parse($calculation?->first_entry_into_circulation_date)->format('d/m/Y') ?? ''}}</b>
                        @else
                            <b></b>
                        @endif
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Distance parcourue en KM
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{number_format($calculation?->mileage ?? 0, 0, ',', ' ') ?? ''}} KMS</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Valeur neuve du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b>{{number_format($evaluation?->vehicle_new_value ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Âge du véhicule en mois
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b> {{number_format($evaluation?->vehicle_age ?? 0, 0, ',', ' ') ?? ''}} mois</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Dépréciation théorique
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b> {{number_format($evaluation?->depreciation_rate ?? 0, 0, ',', ' ') ?? ''}} %</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Plus value kilometrique
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b> {{number_format($evaluation?->kilometric_incidence ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Valeur venale avec incidences kilometrique
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b> {{number_format($evaluation?->vehicle_market_value ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        Valeur venale hors incidences kilometrique
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;">
                        <b> {{number_format($evaluation?->theorical_vehicle_market_value ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
            </thead>
        </table>
    </body>
</html>
