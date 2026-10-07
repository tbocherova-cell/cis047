/* MM,DD,YY/Time: HH:MM:Sec */
const dateElement = document.querySelector("#current-date");
const timeElement = document.querySelector("#current-time");

function updateDateTime() {
    const now = new Date();

    dateElement.textContent = now.toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric"
    });

    timeElement.textContent = now.toLocaleTimeString("en-US", {
        hour: "numeric",
        minute: "2-digit",
        second: "2-digit"
    });
}

updateDateTime();
setInterval(updateDateTime, 1000);

updateDateTime();
setInterval(updateDateTime, 1000);

/* Buttons_My Goals */
const goalsButton = document.querySelector("#goals-toggle");
const extraGoals = document.querySelector("#extra-goals");

goalsButton.addEventListener("click", function () {
    const isHidden = extraGoals.hidden;

    extraGoals.hidden = !isHidden;
    goalsButton.setAttribute("aria-expanded", isHidden);

    if (isHidden) {
        goalsButton.textContent = "Hide My 2026–2027 Goals";
    } else {
        goalsButton.textContent = "Show My 2026–2027 Goals";
    }
});

/* UX/UI Interest Card */
const uxButton = document.querySelector("#ux-toggle");
const uxMore = document.querySelector("#ux-more");

uxButton.addEventListener("click", function () {
    const isHidden = uxMore.hidden;

    uxMore.hidden = !isHidden;
    uxButton.setAttribute("aria-expanded", isHidden);

    if (isHidden) {
        uxButton.textContent = "Show Less";
    } else {
        uxButton.textContent = "Learn More";
    }
});

/* Web Development Learn More button */
const webButton = document.querySelector("#web-toggle");
const webMore = document.querySelector("#web-more");

webButton.addEventListener("click", function () {
    const isHidden = webMore.hidden;

    webMore.hidden = !isHidden;
    webButton.setAttribute("aria-expanded", isHidden);

    if (isHidden) {
        webButton.textContent = "Show Less";
    } else {
        webButton.textContent = "Learn More";
    }
});

/* Life & Hobbies button */
const hobbyButton = document.querySelector("#hobby-button");
const favoriteHobby = document.querySelector("#favorite-hobby");

hobbyButton.addEventListener("click", function () {
    const isHidden = favoriteHobby.hidden;

    favoriteHobby.hidden = !isHidden;
    hobbyButton.setAttribute("aria-expanded", isHidden);

    if (isHidden) {
        hobbyButton.textContent = "Show Less";
    } else {
        hobbyButton.textContent = "Learn More";
    }
});

/* Dark Mode */
const themeButton = document.querySelector("#theme-toggle");

themeButton.addEventListener("click", () => {
    const darkModeOn = document.body.classList.toggle("dark-mode");

    themeButton.setAttribute("aria-pressed", String(darkModeOn));
    themeButton.textContent = darkModeOn ? "Light Mode" : "Dark Mode";
});

/* Filter interest cards */

const filterButtons = document.querySelectorAll(".filter-button");
const interestCards = document.querySelectorAll(".card");

filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const selectedFilter = button.dataset.filter;

        interestCards.forEach((card) => {
            const cardCategory = card.dataset.category;

            if (selectedFilter === "all" || selectedFilter === cardCategory) {
                card.hidden = false;
            } else {
                card.hidden = true;
            }
        });

        filterButtons.forEach((filterButton) => {
            filterButton.classList.remove("active");
        });

        button.classList.add("active");
    });
});