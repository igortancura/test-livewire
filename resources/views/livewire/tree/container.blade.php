<ul  wire:show="isOpen">
    @if(empty($parentId))
        <livewire:tree.element :key="0"/>
    @endif
    @foreach($elements as $element)
        <livewire:tree.element :$element :key="$element->id"/>
    @endforeach
</ul>
