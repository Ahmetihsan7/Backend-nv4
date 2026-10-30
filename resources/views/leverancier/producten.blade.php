<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">
                <h1 class="text-2xl font-normal mb-6">
                    <u>Geleverde producten</u>
                </h1>

                <div class="mb-6 space-y-1 text-base text-gray-900">
                    <p>Naam leverancier: {{ $leverancier->Naam }}</p>
                    <p>Contactpersoon: {{ $leverancier->ContactPersoon }}</p>
                    <p>Leveranciernummer: {{ $leverancier->LeverancierNummer }}</p>
                    <p>Mobiel: {{ $leverancier->Mobiel }}</p>
                </div>

                <table class="w-full border-collapse border border-black text-left text-base mb-6">
                    <thead>
                        <tr>
                            <th class="border border-black p-2 font-normal">Naam product</th>
                            <th class="border border-black p-2 font-normal">Aantal in magazijn</th>
                            <th class="border border-black p-2 font-normal">Verpakkingseenheid</th>
                            <th class="border border-black p-2 font-normal">Laatste levering</th>
                            <th class="border border-black p-2 text-center font-normal">Nieuwe levering</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($heeftProducten)
                            @foreach ($producten as $product)
                                <tr>
                                    <td class="border border-black p-2">{{ $product->Naam }}</td>
                                    <td class="border border-black p-2">{{ $product->AantalAanwezig ?? 0 }}</td>
                                    <td class="border border-black p-2">{{ str_replace('.', ',', (string) ($product->VerpakkingsEenheid ?? 0)) }}</td>
                                    <td class="border border-black p-2">{{ $product->LaatsteLevering ? date('d-m-Y', strtotime($product->LaatsteLevering)) : '' }}</td>
                                    <td class="border border-black p-2 text-center">
                                        <a href="{{ route('levering.create', [$leverancier->Id, $product->ProductId]) }}" class="inline-flex items-center justify-center text-blue-600 hover:text-blue-800" title="Nieuwe levering">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                            </svg>
                                            <span class="sr-only">Nieuwe levering voor {{ $product->Naam }}</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="border border-black p-6 text-center">
                                    {{ $melding }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                @unless ($heeftProducten)
                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('leverancier.index') }}";
                        }, 3000);
                    </script>
                @endunless

                <x-nav-buttons :back="route('leverancier.index')" />
            </div>
        </div>
    </div>
</x-app-layout>
