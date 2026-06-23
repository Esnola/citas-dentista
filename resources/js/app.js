document.addEventListener('click', (event) => {
    const pickerInput = event.target.closest('input[type="date"], input[type="time"]');

    if (! pickerInput || pickerInput.disabled || pickerInput.readOnly || typeof pickerInput.showPicker !== 'function') {
        return;
    }

    try {
        pickerInput.showPicker();
    } catch {
        pickerInput.focus();
    }
});
