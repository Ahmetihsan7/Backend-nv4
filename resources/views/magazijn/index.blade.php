<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Overzicht Magazijn Jamin</u>
                </h1>

                <table class="w-full border-collapse border border-black text-left text-base">
                    <thead>
                        <tr>
                            <th class="border border-black p-2 font-normal">Barcode</th>
                            <th class="border border-black p-2 font-normal">Naam</th>
                            <th class="border border-black p-2 font-normal">Verpakkingseenheid</th>
                            <th class="border border-black p-2 font-normal">Aantalaanwezig</th>
                            <th class="border border-black p-2 text-center font-normal">Allergenen Info</th>
                            <th class="border border-black p-2 text-center font-normal">Leverantie Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producten as $product)
                            <tr>
                                <td class="border border-black p-2">{{ $product->Barcode }}</td>
                                <td class="border border-black p-2">{{ $product->Naam }}</td>
                                <td class="border border-black p-2">{{ str_replace('.', ',', (string)$product->VerpakkingsEenheid) }}</td>
                                <td class="border border-black p-2">{{ $product->AantalAanwezig }}</td>
                                <td class="border border-black p-2 text-center">
                                    <a href="{{ route('allergeen.show', $product->Id) }}" class="text-red-600 font-bold text-xl no-underline">
                                        X
                                    </a>
                                </td>
                                <td class="border border-black p-2 text-center">
                                    <a href="{{ route('levering.show', $product->Id) }}" class="text-blue-600 font-bold text-xl no-underline">
                                        ?
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
