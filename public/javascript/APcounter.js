document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.card[data-counter="true"]').forEach(card => {
        const countEl = card.querySelector('.count');
        const btnInc = card.querySelector('.increase');
        const btnDec = card.querySelector('.decrease');

        if (!countEl || !btnInc || !btnDec) return;

        let count = 0;

        btnInc.addEventListener('click', () => {
            count++;
            countEl.textContent = count;
        });

        btnDec.addEventListener('click', () => {
            if (count > 0) count--;
            countEl.textContent = count;
        });

    });

    document.querySelectorAll('.card[data-counter="false"]').forEach(card => {
        const countEl = card.querySelector('.count');
        const btnInc = card.querySelector('.increase');
        const btnDec = card.querySelector('.decrease');

        if (!countEl || !btnInc || !btnDec) return;

        let count = 0;
        card.dataset.count = count;
        countEl.textContent = (count * 10) + ' min';

        btnInc.addEventListener('click', () => {
            count++;
            card.dataset.count = count;
            countEl.textContent = (count * 10) + ' min';
        });

        btnDec.addEventListener('click', () => {
            if (count > 0) count--;
            card.dataset.count = count;
            countEl.textContent = (count * 10) + ' min';

        });

    });

    document.querySelectorAll('.card[data-type="goals-card"]').forEach(card => {
        const btnSave = card.querySelector('.save-btn');
        const countEl = card.querySelector('.count');

        if (btnSave) {
            btnSave.addEventListener('click', () => {
                const buttonType = btnSave.dataset.type;
                const trueCount = countEl.textContent;
                const payload = { buttonType : buttonType, trueCount : trueCount };

                fetch('/increment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(response => {
                        return response.json().then(data => {
                            if (!response.ok) {
                                throw new Error(data.error || 'Errore di salvataggio');
                            }
                            return data;
                        });
                    })
                    .then(data => {
                        console.log('Salvato:', data);
                        if (data.completed){
                            Swal.fire({
                                icon: 'success',
                                title: 'Complimenti, hai superato con successo il tuo obiettivo!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                        else{
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Counter incrementato con successo',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }

                        const pbContainer = document.querySelector('.cardPB-body');
                        if (data.isTopGoal) {
                            // evita duplicati
                            if (!document.querySelector(`.pb-item[data-goal-id="${data.goal.id}"]`)) {

                                const div = document.createElement('div');
                                div.classList.add('pb-item');
                                div.dataset.goalId = data.goal.id;

                                div.innerHTML = `
                                <label>${data.goal.name} (${data.goal.goalQuantity})</label>
                                <progress
                                    value="${data.goal.percentage}"
                                    max="100"
                                    style="--value: ${data.goal.percentage}; --max: 100;">
                                </progress>
                                `;

                                pbContainer.appendChild(div);
                            }
                        }

                        const el = document.querySelector(`.pb-item[data-goal-id="${data.id}"]`);
                        console.log(el)
                        if (el){

                            if(data.completed || data.percentage > 100){
                                el.remove();
                            }

                            const value = data.percentage > 100 ? 100 : data.percentage;
                            el.querySelector('progress').style.setProperty('--value',value);
                        }

                    })
                    .catch(error => {
                        console.error('Errore:', error);
                        Swal.fire({
                            icon: 'info',
                            title: error.message,
                            confirmButtonColor: '#d33'
                        });
                    })

            });
        }
    });



});
