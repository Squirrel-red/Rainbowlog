document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.experience-detail .photos img');
    images.forEach(image => {
        image.addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.classList.add('image-modal');
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <img src="${this.src}" alt="Imageof real size">
                </div>
            `;
            document.body.appendChild(modal);

            const closeButton = modal.querySelector('.close-button');
            closeButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });

            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        });
    });
});

// Pour sidebar
function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
  }
  
  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
  }



// Pour le rating  des users

document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('rating_rating');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            ratingValue.value = value;
            stars.forEach(s => s.classList.remove('selected'));
            for (let i = 0; i < value; i++) {
                stars[i].classList.add('selected');
            }
        });
    });
});

// Page d'acceuil, animation
document.addEventListener('DOMContentLoaded', function() {
    const annonces = document.querySelectorAll('.experience');
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, options);

    experiences.forEach(experience => {
        observer.observe(experience);
    });
});

// Carrousel 
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du carrousel
    let currentIndex = 0;
    function moveCarousel(direction) {
        const items = document.querySelectorAll('.carousel-item');
        items[currentIndex].style.display = 'none'; // Masquer l'élément actuel
        items[currentIndex].classList.remove('active'); // Retirer la classe active

        currentIndex = (currentIndex + direction + items.length) % items.length; // Calculer le nouvel index
        items[currentIndex].style.display = 'block'; // Afficher le nouvel élément
        items[currentIndex].classList.add('active'); // Ajouter la classe active
    }
    // Afficher l'élément initial
    document.querySelectorAll('.carousel-item').forEach((item, index) => {
        item.style.display = index === currentIndex ? 'block' : 'none';
    });
    // Gestion des clics sur les boutons de navigation
    document.querySelector('.carousel-button.prev').addEventListener('click', function() {
        moveCarousel(-1);
    });
    document.querySelector('.carousel-button.next').addEventListener('click', function() {
        moveCarousel(1);
    });
    // Défilement automatique
    setInterval(() => {
        moveCarousel(1);
    }, 3000); // Changer d'élément toutes les 3 secondes
});

// Les derniers commentaires pour la page d'acceuil

document.addEventListener('DOMContentLoaded', function() {
    const commentsSection = document.querySelector('.comments-section');
    commentsSection.style.opacity = 0;
    commentsSection.style.transform = 'translateY(20px)';

    setTimeout(() => {
        commentsSection.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        commentsSection.style.opacity = 1;
        commentsSection.style.transform = 'translateY(0)';
    }, 100); // Délai avant l'animation
});


// Gérer le modal pour afficher la description de l'experience

function openDescriptionModal(description) {
    const modal = document.createElement('div');
    modal.classList.add('modal');
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-button" onclick="this.parentElement.parentElement.remove()">&times;</span>
            <p>${description}</p>
        </div>
    `;
    document.body.appendChild(modal);
}
