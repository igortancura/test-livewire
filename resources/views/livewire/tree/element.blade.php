<li class="pl-2 relative">
    @if($element->exists)
        <button wire:show="isParent" wire:click="toggle">
            <svg class="{{$isOpen?'strelka-bottom':'strelka-right'}}" viewBox="0 0 9 14">
                <path
                    d="M6.660,8.922 L6.660,8.922 L2.350,13.408 L0.503,11.486 L4.813,7.000 L0.503,2.515 L2.350,0.592 L8.507,7.000 L6.660,8.922 Z"></path>
            </svg>
        </button>
        <span class="{{$isParent?'':'pl-6'}}">{{$element->name}}</span>
        @foreach(\App\Enums\Tree\FormType::cases() as $type)
            <button class="action-button" wire:click="openForm({{$type}}, {{$element->id}})">@lang('tree.container.actions.'.$type->value)</button>
        @endforeach

        @php($parentId=$element->id)

        <livewire:tree.container :$parentId/>
    @else
        <span >@lang('tree.container.root')</span>
        @foreach([\App\Enums\Tree\FormType::ADD] as $type)
            <button class="action-button" wire:click="openForm({{$type}}, 0)">@lang('tree.container.actions.'.$type->value)</button>
        @endforeach
    @endif
</li>
