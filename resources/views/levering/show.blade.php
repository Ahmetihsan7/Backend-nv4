<x-app-layout>
    @if (!$heeftVoorraad)
        <x-slot name="head">
            <meta http-equiv="refresh" content="4; url={{ route('magazijn.index') }}">
        </x-slot>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>LeveringsInformatie</u>
                </h1>

                @if ($heeftVoorraad)
                    <div class="mb-6 space-y-1 text-base text-gray-900">
                        <p>Naam Leverancier: {{ $leverancier->LeverancierNaam ?? '' }}</p>
                        <p>Contactpersoon leverancier: {{ $leverancier->ContactPersoon ?? '' }}</p>
                        <p>Leverancier nummer: {{ $leverancier->LeverancierNummer ?? '' }}</p>
                        <p>Mobiel: {{ $leverancier->Mobiel ?? '' }}</p>
                    </div>

                    <table class="w-full border-collapse border border-black text-left text-base">
                        <thead>
                            <tr>
                                <th class="border border-black p-2 font-normal">Naam Product</th>
                                <th class="border border-black p-2 font-normal">Datum laatste levering</th>
                                <th class="border border-black p-2 font-normal">Aantal</th>
                                <th class="border border-black p-2 font-normal">Eerstvolgende levering</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leveringen as $levering)
                                <tr>
                                    <td class="border border-black p-2">{{ $product->Naam }}</td>
                                    <td class="border border-black p-2">{{ date('d-m-Y', strtotime($levering->DatumLevering)) }}</td>
                                    <td class="border border-black p-2">{{ $levering->Aantal }}</td>
                                    <td class="border border-black p-2">
                                        @if ($levering->DatumEerstVolgendeLevering)
                                            {{ date('d-m-Y', strtotime($levering->DatumEerstVolgendeLevering)) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @else
                    <table class="w-full border-collapse border border-black text-center text-base mb-4">
                        <tbody>
                            <tr>
                                <td class="border border-black p-6">
                                    {{ $geenVoorraadMelding }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('magazijn.index') }}";
                        }, 4000);
                    </script>
                @endif

                <x-nav-buttons :back="route('magazijn.index')" />

            </div>
        </div>
    </div>
</x-app-layout>
