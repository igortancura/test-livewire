<div class="{{$isOpen?'':'hidden'}} relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <form wire:submit="submit({{ $typeForm }}, {{ $elementId }})" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">


                <div class="bg-gray-50 px-4 py-3 sm:px-6">
                    <label for="name-edit-form-tree" class="block">@lang('tree.forms.labels_name.'.$typeForm)</label>
                    <input type="text" wire:model="form.name" id="name-edit-form-tree" class=" border-1 border-sky-500 outline-sky-500 ">
                    <div class="text-red-600">@error('form.name') {{ $message }} @enderror</div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit"
                            class="inline-flex w-full justify-center rounded-md  px-3 py-2 text-sm font-semibold text-white shadow-xs {{ $typeForm==\App\Enums\Tree\FormType::DELETE->value?'bg-red-600 hover:bg-red-500':'bg-blue-600 hover:bg-blue-500' }} sm:ml-3 sm:w-auto">
                        @lang('tree.forms.buttons.actions.'.$typeForm)
                    </button>
                    <button wire:click="closeForm" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        @lang('tree.forms.buttons.cancel')
                    </button>
                    <span class="form-delete-timer {{ $isStartTimer? '' : 'hidden' }} mt-3 w-full justify-center  px-3 py-2 text-sm font-semibold text-red-600 sm:mt-0 sm:w-auto">{{$startingTimer}}</span>
                </div>
                @if(session('action_error'))
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 text-red-600">{{session('action_error')}}</div>
                @endif
            </form>
        </div>
    </div>
</div>

@script
<script>
    const formTimer = {
        id: null,
        startingTimer: {{ $startingTimer }}
    }

    $wire.on('start-timer', () => {
        let momentTimer = formTimer.startingTimer
        let elementTimer = $wire.$el.querySelector('.form-delete-timer');

        formTimer.id = setInterval(() => {
            momentTimer -= 1;
            elementTimer.textContent = momentTimer;
            if (momentTimer <= 0) {
                clearInterval(formTimer.id);
                $wire.$call('closeForm');
            }
        }, 1000)
    });

    $wire.on('close-form', () => {
        if (formTimer.id != null) {
            clearInterval(formTimer.id);
            formTimer.id = null;
        }
    });
</script>
@endscript
