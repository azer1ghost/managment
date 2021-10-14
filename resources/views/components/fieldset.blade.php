<fieldset id="step-{{$step}}">
    <div class="form-card">
        <div class="row align-items-center">
            <div class="col-7">
                <h2 class="fs-title">@lang('translates.register.progress.' . $header):</h2>
            </div>
            <div class="col-5">
                <h4 class="steps">@lang('translates.register.steps', ['step' => $step])</h4>
            </div>
        </div>
        {{$slot}}
    </div>
    <div class="row justify-content-end align-items-center">
        <div class="spinner-border text-primary mr-3 d-none" role="status"></div>
        <input type="button" name="previous" class="previous action-button-previous mr-2" value="@lang('translates.buttons.previous')" />
        <input type="button" name="next" class="next action-button" value="@lang('translates.buttons.next')" />
    </div>
</fieldset>