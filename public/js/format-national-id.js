function formatNationalID(input) {
    let value = input.value.replace(/\D/g, ''); // Remove non-digits
    if (value.length > 12) value = value.slice(0, 12);

    let formatted = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 3 === 0) formatted += ' ';
        formatted += value[i];
    }

    input.value = formatted;
}