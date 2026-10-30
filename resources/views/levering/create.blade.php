<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">
                <h1 class="text-2xl font-normal mb-6">
                    <u>Levering product</u>
                </h1>

                <p class="mb-4 text-base text-gray-900">
                    Productnaam: <strong>{{ $product->Naam }}</strong><br>
                    Leverancier: <strong>{{ $leverancier->Naam }}</strong><br>
                    Contactpersoon: <strong>{{ $leverancier->ContactPersoon }}</strong><br>
                    Mobiel: <strong>{{ $leverancier->Mobiel }}</strong>
                </p>

                @if (session('inactive_product_error'))
                    <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-700">
                        {{ session('inactive_product_error') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('leverancier.producten', $leverancier->Id) }}";
                        }, 4000);
                    </script>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('levering.store', [$leverancier->Id, $product->Id]) }}">
                    @csrf

                    <div class="mb-5 text-base text-gray-900">
                        <p>Datum levering: <strong>{{ date('d-m-Y', strtotime($datumLevering)) }}</strong></p>
                        <p>Huidige voorraad: <strong>{{ $magazijn->AantalAanwezig ?? 0 }}</strong></p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="aantal" class="block text-sm font-medium text-gray-700">Aantal producteenheden</label>
                            <input id="aantal" name="aantal" type="number" min="1" max="4294967295" step="1" required
                                value="{{ old('aantal') }}"
                                class="mt-1 block w-[250px] max-w-full rounded border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                        </div>

                        <div>
                            <label for="datum_eerst_volgende_levering" class="block text-sm font-medium text-gray-700">Datum eerstvolgende levering</label>
                            <input id="datum_eerst_volgende_levering" name="datum_eerst_volgende_levering" type="date" min="{{ $datumLevering }}" required
                                value="{{ old('datum_eerst_volgende_levering') }}"
                                class="mt-1 block w-[250px] max-w-full rounded border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="mt-6 flex gap-4">
                        <button type="submit" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                            Sla op
                        </button>
                        <x-nav-buttons :back="route('leverancier.producten', $leverancier->Id)" wrapper-class="contents" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
