<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">
                <h1 class="text-2xl font-normal mb-6">
                    <u>Overzicht Leveranciers</u>
                </h1>

                <table class="w-full border-collapse border border-black text-left text-base">
                    <thead>
                        <tr>
                            <th class="border border-black p-2 font-normal">Naam</th>
                            <th class="border border-black p-2 font-normal">Contactpersoon</th>
                            <th class="border border-black p-2 font-normal">Leveranciernummer</th>
                            <th class="border border-black p-2 font-normal">Mobiel</th>
                            <th class="border border-black p-2 font-normal">Aantal verschillende producten</th>
                            <th class="border border-black p-2 text-center font-normal">Toon producten</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leveranciers as $leverancier)
                            <tr>
                                <td class="border border-black p-2">{{ $leverancier->Naam }}</td>
                                <td class="border border-black p-2">{{ $leverancier->ContactPersoon }}</td>
                                <td class="border border-black p-2">{{ $leverancier->LeverancierNummer }}</td>
                                <td class="border border-black p-2">{{ $leverancier->Mobiel }}</td>
                                <td class="border border-black p-2">{{ (int) ($leverancier->AantalProducten ?? 0) }}</td>
                                <td class="border border-black p-2 text-center">
                                    <a href="{{ route('leverancier.producten', $leverancier->Id) }}" class="inline-flex items-center justify-center text-blue-600 hover:text-blue-800" title="Toon producten voor {{ $leverancier->Naam }}">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5 12 3.75l8.25 3.75M3.75 7.5V18L12 21.75 20.25 18V7.5M3.75 7.5 12 11.25m8.25-3.75L12 11.25m0 0v10.5" />
                                        </svg>
                                        <span class="sr-only">Toon producten voor {{ $leverancier->Naam }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <x-nav-buttons :back="route('dashboard')" />
            </div>
        </div>
    </div>
</x-app-layout>
