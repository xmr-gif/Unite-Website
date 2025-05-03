document.addEventListener('DOMContentLoaded', function() {
    const subjectCards = document.querySelectorAll('.subject-card');
    const subjectDetailsModal = document.getElementById('subjectDetailsModal');
    const modalSubjectTitle = document.getElementById('modalSubjectTitle');
    const modalProfessorName = document.getElementById('modalProfessorName');
    const modalSubjectDescription = document.getElementById('modalSubjectDescription');
    const closeSubjectModalButton = document.getElementById('closeSubjectModalButton');
    const closeSubjectModalOutside = document.getElementById('closeSubjectModal');

    subjectCards.forEach(card => {
        const detailsButton = card.querySelector('.text-indigo-500'); // Select the details button within the card
        if(detailsButton){
            detailsButton.addEventListener('click', function() {
                const title = card.dataset.subjectTitle;
                const professor = card.dataset.professorName;
                const description = card.dataset.subjectDescription;

                modalSubjectTitle.textContent = title;
                modalProfessorName.textContent = professor;
                modalSubjectDescription.textContent = description;

                subjectDetailsModal.classList.remove('hidden');
            });
        }

    });

    closeSubjectModalButton.addEventListener('click', function() {
        console.log('Close button clicked!');
        subjectDetailsModal.classList.add('hidden');
    });

    closeSubjectModalOutside.addEventListener('click', function() {
        console.log('Close button clicked!');
        subjectDetailsModal.classList.add('hidden');
    });

});
