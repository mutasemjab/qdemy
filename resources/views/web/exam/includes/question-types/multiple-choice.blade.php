<div class="multiple-choice-options">
    @if($question->options)
        @foreach($question->options as $index => $option)
            <label class="option-label">
                <input type="radio"
                       name="answer[]"
                       value="{{ $option->id }}"
                       class="option-radio"
                       @if(isset($currentAnswer) && in_array($option->id, (array)$currentAnswer->selected_options)) checked @endif>
                <div class="option-content">
                    <div class="option-indicator">{{ chr(65 + $index) }}</div>
                    <div class="option-text">{{ $option->option }}</div>
                </div>
            </label>
        @endforeach
    @endif
</div>