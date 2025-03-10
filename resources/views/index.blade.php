@extends('layouts.base')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl w-full">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h4 class="text-2xl font-bold text-center text-white">Sistema de Caja</h4>
                </div>
                <div class="bg-white p-8">
                    <div class="mb-6">
                        <label for="monto" class="block text-gray-700 font-bold mb-2">Monto:</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-lg">$</span>
                            </div>
                            <input type="number"
                                id="monto"
                                name="monto"
                                step="0.01"
                                class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-lg text-gray-900 bg-white"
                                placeholder="Ingrese el monto">
                        </div>
                    </div>

                    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg" id="mensaje" role="alert">
                        <!-- Aquí se mostrará el mensaje -->
                    </div>

                    <div class="space-y-4">
                        <form method="POST" action="">
                            <button id="btnAbrirCaja"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-cash-register mr-2"></i>
                            Abrir Caja
                            </button>
                        </form>
                        <button id="btnAgregarBilletes"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Agregar Billetes
                        </button>
                        <button id="btnCanjearCheque"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-money-check mr-2"></i>
                            Canjear Cheque
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
