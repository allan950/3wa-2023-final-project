const incrQty = document.getElementById("increment-qty");
const decrQty = document.getElementById("decrement-qty");
const qtyBox = document.getElementById("qty-textbox");

incrQty.addEventListener('click', () => {
    qtyBox.value += 1;
});

decrQty.addEventListener('click', () => {
    qtyBox.value -= 1;
});