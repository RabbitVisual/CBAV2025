@if(session('success'))
    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600 dark:text-green-400"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2 text-red-600 dark:text-red-400"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-start mb-2">
            <i class="fas fa-exclamation-triangle mr-2 mt-0.5 text-red-600 dark:text-red-400 flex-shrink-0"></i>
            <div>
                <strong class="font-semibold">Erro de Validação!</strong>
                <span class="text-sm">Por favor, verifique os campos abaixo:</span>
            </div>
        </div>
        <ul class="list-disc list-inside text-sm space-y-1 ml-6">
            @foreach($errors->all() as $error)
                <li class="text-red-700 dark:text-red-300">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif