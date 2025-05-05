<ul class="{{ !empty($parentId)?'ml-2 border-l-1 border-l-black':'' }}" wire:show="isOpen">
    @if(empty($parentId))
        <livewire:tree.element :key="0"/>
    @endif
    @foreach($elements as $element)
        <livewire:tree.element :$element :key="$element->id"/>
    @endforeach
</ul>
