export function formDate(date) {
    return date.toISOString().split('T')[0];
}