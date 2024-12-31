<div class="overflow-x-auto">
    <table id="tabla" class="stripe hover min-w-full divide-y divide-gray-200 table-auto display">
        <thead class="bg-indigo-400 text-white">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $body }}
        </tbody>
        <tfoot>
            <tr>
                {{ $header }}
            </tr>
        </tfoot>
    </table>
</div>
