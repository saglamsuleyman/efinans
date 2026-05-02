const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');

if (menuToggle && navLinks) {
    menuToggle.addEventListener('click', () => {
        const isOpen = navLinks.classList.toggle('open');
        menuToggle.setAttribute('aria-expanded', String(isOpen));
    });
}

document.querySelectorAll('.nav-dropdown > button').forEach((button) => {
    button.addEventListener('click', () => {
        const dropdown = button.closest('.nav-dropdown');
        if (dropdown) {
            dropdown.classList.toggle('open');
        }
    });
});

document.querySelectorAll('[data-confirm]').forEach((link) => {
    link.addEventListener('click', (event) => {
        if (!confirm(link.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    });
});

const money = new Intl.NumberFormat('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

function readNumber(id) {
    const element = document.getElementById(id);
    return element ? Number(element.value) || 0 : 0;
}

document.querySelectorAll('[data-calc]').forEach((button) => {
    button.addEventListener('click', () => {
        const type = button.getAttribute('data-calc');

        if (type === 'fx') {
            const result = readNumber('fxAmount') * readNumber('fxRate');
            document.getElementById('fxResult').textContent = `${money.format(result)} TL`;
        }

        if (type === 'simple') {
            const principal = readNumber('simplePrincipal');
            const interest = principal * (readNumber('simpleRate') / 100) * readNumber('simpleYears');
            document.getElementById('simpleResult').textContent = `Faiz: ${money.format(interest)} TL | Toplam: ${money.format(principal + interest)} TL`;
        }

        if (type === 'compound') {
            const principal = readNumber('compoundPrincipal');
            const rate = readNumber('compoundRate') / 100;
            const years = readNumber('compoundYears');
            const times = Math.max(readNumber('compoundTimes'), 1);
            const total = principal * Math.pow(1 + rate / times, times * years);
            document.getElementById('compoundResult').textContent = `Toplam: ${money.format(total)} TL | Kazanç: ${money.format(total - principal)} TL`;
        }

        if (type === 'loan') {
            const amount = readNumber('loanAmount');
            const rate = readNumber('loanRate') / 100;
            const months = Math.max(readNumber('loanMonths'), 1);
            const payment = rate === 0 ? amount / months : amount * (rate * Math.pow(1 + rate, months)) / (Math.pow(1 + rate, months) - 1);
            document.getElementById('loanResult').textContent = `Aylık taksit: ${money.format(payment)} TL | Toplam ödeme: ${money.format(payment * months)} TL`;
        }
    });
});
