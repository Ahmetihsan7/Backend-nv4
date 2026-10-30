<x-app-layout>
    @if (!$heeftAllergenen)
        <x-slot name="head">
            <meta http-equiv="refresh" content="4; url={{ route('magazijn.index') }}">
        </x-slot>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Overzicht Allergenen</u>
                </h1>

                <div class="mb-6 space-y-1 text-base text-gray-900">
                    <p>Naam: {{ $product->Naam }}</p>
                    <p>Barcode: {{ $product->Barcode }}</p>
                </div>

                @if ($heeftAllergenen)
                    <table class="w-full border-collapse border border-black text-left text-base">
                        <thead>
                            <tr>
                                <th class="border border-black p-2 font-normal w-1/4">Naam</th>
                                <th class="border border-black p-2 font-normal w-3/4">Omschrijving</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allergenen as $allergeen)
                                <tr>
                                    <td class="border border-black p-2">{{ $allergeen->Naam }}</td>
                                    <td class="border border-black p-2">{{ $allergeen->Omschrijving }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @else
                    <table class="w-full border-collapse border border-black text-center text-base mb-4">
                        <tbody>
                            <tr>
                                <td class="border border-black p-6">
                                    {{ $geenAllergenenMelding }}
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
