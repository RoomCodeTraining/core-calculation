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

        <table class="table table-bordered text-center" style="width: 100%; table-layout: fixed;">
            <thead style="border: 1px solid; font-size: 12px;">
                <tr style="border: 1px solid; font-size: 12px;">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle; width: 33.33%;">
                        <img src="{{$logo}}" alt="logo" style="text-align: center; width:170px; height:110px;">
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle; width: 33.33%;">
                        <b>ESTIMATION DE VALEUR VENALE <br><br> <span class="text-danger">N° {{$calculation->reference ?? ''}}</span></b>
                    </th>
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle; width: 33.33%;">
                        <b>Assuré: mmatriculation Vehicul mmatriculation Vehicul {{ mb_strtoupper($calculation?->insured ?? '') }}</b>
                        <br><br>
                        DATE: {{ \Carbon\Carbon::parse($calculation?->created_at)->format('d/m/Y') }}

                    </th>
                </tr>
            </thead>
        </table>

        <table class="table text-center" style="border-spacing: 0px; width: 100%; table-layout: fixed;">
            <thead style="border: 1px solid; font-size: 12px; border-spacing: 0px;">
                <tr style="border: 1px solid; font-size: 12px; background-color: rgb(204, 255, 204);">
                    <th style="border: 1px solid; font-size: 12px; vertical-align: middle;" colspan="2">INFORMATIONS</span></th>
                </tr>
            </thead>
            <thead style="border: 1px solid; font-size: 11px; border-spacing: 0px;">
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Immatriculation Vehicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->license_plate ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        N° Serie Vehicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->serial_number ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                    Marque du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleModel?->brand?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Modèle du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleModel?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Nom commercial du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleModel?->description ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Genre du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleGenreUsage?->vehicleGenre?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Usage du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleGenreUsage?->usage?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Energie du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->vehicleEnergy?->label ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Puissance fiscale du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{number_format($calculation?->vehicleCharacteristic?->fiscal_power ?? 0, 0, ',', ' ')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Nombre de places du véhicule 
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{number_format($calculation?->vehicleCharacteristic?->nb_seats ?? 0, 0, ',', ' ')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Type de véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->type ?? '')}}</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Equipement du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>
                            <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->equipments ?? '')}}</b>
                        </b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Options du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>
                            <b>{{mb_strtoupper($calculation?->vehicleCharacteristic?->options ?? '')}}</b>
                        </b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Date de première mise en circulation du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        @if($calculation?->first_entry_into_circulation_date)
                            <b>{{ \Carbon\Carbon::parse($calculation?->first_entry_into_circulation_date)->format('d/m/Y') ?? ''}}</b>
                        @else
                            <b></b>
                        @endif
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Distance parcourue en KM
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{number_format($calculation?->mileage ?? 0, 0, ',', ' ') ?? ''}} KMS</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Valeur neuve du véhicule
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b>{{number_format($evaluation?->vehicle_new_value ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Âge du véhicule en mois
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b> {{number_format($evaluation?->vehicle_age ?? 0, 0, ',', ' ') ?? ''}} mois</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Dépréciation théorique
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b> {{number_format($evaluation?->depreciation_rate ?? 0, 2, ',', ' ') ?? ''}} %</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Plus value kilometrique
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b> {{number_format($evaluation?->kilometric_incidence ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Valeur vénale avec incidences kilometrique
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b> {{number_format($evaluation?->vehicle_market_value ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
                <tr style="border: 1px solid; font-size: 11px;">
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 30%;">
                        Valeur vénale hors incidences kilometrique
                    </th>
                    <th style="border: 1px solid; font-size: 11px; vertical-align: middle; width: 70%;">
                        <b> {{number_format($evaluation?->theorical_vehicle_market_value ?? 0, 0, ',', ' ') ?? ''}} FCFA</b>
                    </th>
                </tr>
            </thead>
        </table>
        <p style="font-size: 12px; text-align: left; margin-top: 10px; justify-content: justify;">
            <b>NB:</b> Cette valeur est donnée à titre indicatif sur les bases d’un véhicule considéré à l’état standard et en état de marche. En
                aucun cas elle ne peut se substituer à une valeur à “dire d’expert” établie par un Expert en automobile qui aura au préalable
                examiné le véhicule en tenant compte de toutes les règles professionnelles en vigueur sur le sujet.
                <br>
                Elle est calculée à partir des informations transmises par l’utilisateur de l’application lors de l’identification du véhicule.
        </p>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="padding-bottom: 2px;">
                    <span style="display:inline-block;width:10px;height:10px;border:1px solid #000;margin-right:5px;"></span>
                    J'accepte la valeur
                </td>
            </tr>
            <tr>
                <td>
                    <span style="display:inline-block;width:10px;height:10px;border:1px solid #000;margin-right:5px;"></span>
                    Je n'accepte pas la valeur
                </td>
            </tr>
        </table>

        <p style="font-size: 12px;">
            Le choix d'une des options implique l'acceptation de toutes les conséquences liées à ce choix dans l'application des conditions générales d'assurances.
        </p>

        <p style="font-size: 12px; margin-top: 40px; text-align: right;">
            Signature de l'assuré
        </p>
    </body>
</html>
