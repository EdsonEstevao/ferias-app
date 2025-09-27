<x-app-layout>
    <div x-data="painelVinculo()" x-init="carregar()" class="max-w-5xl p-6 mx-auto mt-6 bg-white rounded shadow">
        {{-- <div x-data="painelVinculo()" class="max-w-5xl p-6 mx-auto mt-6 bg-white rounded shadow"> --}}
        <h2 class="mb-4 text-xl font-bold text-gray-800">📌 Cadastro de Vínculos</h2>

        <!-- Mensagem -->
        <template x-if="mensagem">
            <div class="mb-4 font-semibold text-green-600" x-text="mensagem" x-show="mensagem" x-transition></div>
        </template>

        <!-- Formulário -->
        <form @submit.prevent="salvar()" class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3" method="POST">
            @csrf
            <div>
                <label class="text-sm font-medium">Secretaria</label>
                <select x-model="form.secretaria_id" class="w-full mt-1 border rounded" name="secretaria_id" required>
                    <option value="">Selecione</option>
                    <template x-for="s in secretarias" :key="s.id">
                        <option :value="s.id" x-text="s.sigla"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Cargo</label>
                <select x-model="form.cargo_id" class="w-full mt-1 border rounded" name="cargo_id" required>
                    <option value="">Selecione</option>
                    <template x-for="c in cargos" :key="c.id">
                        <option :value="c.id" x-text="c.nome"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Simbologia</label>
                <select x-model="form.simbologia_id" class="w-full mt-1 border rounded" name="simbologia_id" required>
                    <option value="">Selecione</option>
                    <template x-for="s in simbologias" :key="s.id">
                        <option :value="s.id" x-text="s.nome"></option>
                    </template>
                </select>
            </div>

            <div class="flex justify-end md:col-span-3">
                <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                    Salvar Vínculo
                </button>
            </div>
        </form>

        <!-- Filtros -->
        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">

            {{-- <input type="text" x-model.debounce.300ms="filtro.secretaria" placeholder="Filtrar por secretaria"> --}}
            <select x-model="filtro.secretaria" class="px-3 py-2 border rounded">
                <option value="">Todas as secretarias</option>
                <template x-for="s in secretarias" :key="s.id">
                    <option :value="s.sigla" x-text="s.sigla"></option>
                </template>
            </select>
            <input type="text" x-model="filtro.cargo" placeholder="Filtrar por cargo"
                class="px-3 py-2 border rounded">
        </div>

        <!-- Listagem -->
        <template x-if="vinculos.length">
            <div class="space-y-2">
                <template x-for="v in vinculosFiltrados()" :key="v.id">
                    <div class="p-3 border rounded shadow-sm">
                        {{-- <p class="font-semibold text-gray-800" x-text="v.secretaria.sigla"></p> --}}
                        {{-- <p class="text-sm text-gray-600"> --}}
                        <p class="font-semibold text-gray-800">
                            Cargo: <span x-text="v.cargo.nome"></span>

                        </p>
                        <p class="text-xs text-gray-600">
                            <span x-text="v.simbologia.nome"></span> - (<span
                                x-text="formatarMoeda(v.simbologia.valor)"></span>)
                        </p>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!vinculos.length">
            <p class="mt-4 text-gray-500">Nenhum vínculo cadastrado.</p>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('painelVinculo', () => ({
                secretarias: {{ Js::from($secretarias) }},
                cargos: {{ Js::from($cargos) }},
                simbologias: {{ Js::from($simbologias) }},
                vinculos: {{ Js::from($vinculos) }},
                mensagem: '',
                form: {
                    secretaria_id: '',
                    cargo_id: '',
                    simbologia_id: ''
                },
                filtro: {
                    secretaria: '',
                    cargo: ''
                },

                carregar() {
                    Promise.all([
                        // fetch('/api/secretarias').then(res => res.json()),
                        // fetch('/api/cargos').then(res => res.json()),
                        // fetch('/api/simbologias').then(res => res.json()),
                        // fetch('/api/vinculos').then(res => res.json())
                        fetch("{{ route('vinculos.json') }}").then(res => res
                            .json())
                        // ]).then(([secretarias, cargos, simbologias, vinculos]) => {
                    ]).then(([vinculos]) => {
                        // this.secretarias = secretarias;
                        // this.cargos = cargos;
                        // this.simbologias = simbologias;
                        this.vinculos = vinculos;
                    });
                },
                salvar() {
                    fetch('/api/vinculos', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(this.form)
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.mensagem = data.status === 'updated' ?
                                '✅ Vínculo atualizado com sucesso!' :
                                '✅ Vínculo criado com sucesso!';

                            // Atualiza ou adiciona na lista
                            const index = this.vinculos.findIndex(v =>
                                v.secretaria_id === data.vinculo.secretaria_id &&
                                v.cargo_id === data.vinculo.cargo_id
                            );

                            if (index !== -1) {
                                this.vinculos[index] = data.vinculo;
                            } else {
                                this.vinculos.push(data.vinculo);
                            }

                            this.form.secretaria_id = this.form.cargo_id = this.form.simbologia_id =
                                '';
                            setTimeout(() => this.mensagem = '', 5000);
                        });
                },

                // salvar() {
                //     fetch('/api/vinculos', {
                //             method: 'POST',
                //             headers: {
                //                 'Content-Type': 'application/json',
                //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                //                     .getAttribute('content')
                //             },
                //             body: JSON.stringify(this.form)
                //         })
                //         .then(res => res.json())
                //         .then(data => {
                //             this.mensagem = 'Vínculo cadastrado com sucesso!';
                //             this.vinculos.push(data);
                //             this.form.secretaria_id = this.form.cargo_id = this.form.simbologia_id =
                //                 '';
                //             setTimeout(() => this.mensagem = '', 5000);
                //         });
                // },

                vinculosFiltrados() {
                    // return this.vinculos.filter(v =>
                    //     v.secretaria.nome.toLowerCase().includes(this.filtro.secretaria
                    //         .toLowerCase()) &&
                    //     v.cargo.nome.toLowerCase().includes(this.filtro.cargo.toLowerCase())
                    // );
                    const normalize = str => str?.toLowerCase().normalize("NFD").replace(
                        /[\u0300-\u036f]/g, "") || "";

                    return this.vinculos.filter(v =>
                        normalize(v.secretaria.sigla).includes(normalize(this.filtro.secretaria)) &&
                        normalize(v.cargo.nome).includes(normalize(this.filtro.cargo))
                    );
                },
                formatarMoeda(valor) {
                    if (!valor) return 'R$ 0,00';
                    return new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).format(valor);
                }
            }));
        });
    </script>

</x-app-layout>
