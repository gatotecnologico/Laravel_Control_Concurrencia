@extends('layouts.base')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-gray-800 rounded-t-2xl shadow-lg p-6 border-b-2 border-purple-500">
            <h1 class="text-3xl font-bold text-center text-white">Sistema de Control de Caja</h1>
            <p class="text-center text-gray-400 mt-2">Gestión de Efectivo y Transacciones</p>
        </div>

        <!-- Main Content -->
        <div class="bg-gray-800 shadow-lg rounded-b-2xl p-8">
            <!-- Amount Input Section -->
            <div class="mb-8">
                <div class="bg-gray-900 p-6 rounded-xl border border-gray-700">
                    <label for="monto" class="text-lg font-semibold text-gray-300 block mb-3">
                        Monto a Procesar
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-xl font-medium">$</span>
                        </div>
                        <input type="number"
                            id="monto"
                            name="monto"
                            step="0.01"
                            class="block w-full pl-10 pr-4 py-4 text-xl bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                            placeholder="0.00">
                    </div>
                </div>
            </div>

            <!-- Status Message -->
            <div class="mb-8">
                <div class="bg-gray-900 border-l-4 border-purple-500 p-4 rounded-lg" id="mensaje" role="alert">
                    <p class="text-gray-300"></p>
                </div>
            </div>

            <!-- Action Buttons Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <form method="GET" action="{{ route('teller.abrirCaja', ['sucursal' => 1]) }}" class="md:col-span-2">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-lg font-bold py-4 px-6 rounded-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl flex items-center justify-center space-x-3">
                        <i class="fas fa-cash-register text-2xl"></i>
                        <span>Abrir Caja</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('teller.agregarBilletes', ['sucursal' => 1]) }}">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-lg font-bold py-4 px-6 rounded-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl flex items-center justify-center space-x-3">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                        <span>Agregar Billetes</span>
                    </button>
                </form>

                <button type="button"
                    class="w-full bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-lg font-bold py-4 px-6 rounded-xl transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl flex items-center justify-center space-x-3">
                    <i class="fas fa-money-check text-2xl"></i>
                    <span>Canjear Cheque</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
