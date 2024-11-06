
import { movieUrl, countryUrl, genreUrl } from "./routes.js";

document.addEventListener('DOMContentLoaded', function() {
    let movieId = null; // Variable global para almacenar el id

    //Module movies
    const $sectionMovies = document.getElementById('module-movies');
    const $sectionDetailsMovies = document.getElementById('module-details-movie');
    const $sectionAddEditMovie = document.getElementById('module-add-edit');

    const $closeButtons = document.querySelectorAll('.btn-close');
    const $containerDetails = document.getElementById('container-details');
    const $containerImg = document.getElementById('container-img');

    const $moviesContainer = document.getElementById('cart-Allmovies');

    const $btnAdd = document.getElementById('btn-add');
    const $btnAddPerson = document.getElementById('btn-add-person');

    const $peopleContainer = document.getElementById("peopleContainer");
    const $formMovie = document.getElementById('movieForm');


    let $title = document.getElementById('title');
    let $year = document.getElementById('year');
    let $url = document.getElementById('url');
    let $country = document.getElementById('country');

    fillCountries();
    fillGenres();
    fillMovies();

    $btnAdd.addEventListener('click',function(){
        $sectionAddEditMovie.classList.remove("hidden");
        $sectionMovies.classList.add("hidden");
        $sectionDetailsMovies.classList.add("hidden");

        $formMovie.reset();
        $title.disabled = false;

        $peopleContainer.innerHTML = '';
        document.getElementById('title-add-edit').innerHTML = "Agregar película";
        $btnAddPerson.disabled = false;
    });


    $closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            $sectionAddEditMovie.classList.add("hidden");
            $sectionDetailsMovies.classList.add("hidden");
            $sectionMovies.classList.remove('hidden');
        });
    });



    $btnAddPerson.addEventListener('click',function(){
        const personEntry = `
            <div class="card m-2" style="width: 18rem;">
                <div class="card-body">
                    <input type="text" class="form-control mb-2" placeholder="Nombre" required>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input is-director">
                        <label class="form-check-label">Es Director</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input is-protagonist">
                        <label class="form-check-label">Es Protagonista</label>
                    </div>
                    <button type="button" class="btn btn-danger removePerson">Eliminar</button>
                </div>
            </div>
        `;

        $peopleContainer.insertAdjacentHTML('beforeend', personEntry);
        

    });
    
    $peopleContainer.addEventListener('click', function(event) {

        if (event.target && event.target.classList.contains('removePerson')) {
            const card = event.target.closest('.card');
            if (card) {
                card.remove(); 
            }
        }
    });


    $formMovie.addEventListener('submit', function (event) {
        event.preventDefault();

        const titleBox = document.getElementById('title-add-edit').textContent;

        const selectedGenres = [];
            const checkboxes = document.querySelectorAll('input[name="genre"]:checked'); 
            checkboxes.forEach(checkbox => {
                selectedGenres.push(checkbox.value);
            });
            if (selectedGenres.length === 0) {
                alert("Por favor, selecciona al menos un género.");
                return;
            }
            const cards = $peopleContainer.querySelectorAll('.card');
            if (cards.length === 0) {
                alert('Debes agregar al menos una persona.');
                return;
            }

        if(titleBox === "Agregar película"){
            const newMovie = {
                title: $title.value,
                country: $country.value,
                url: $url.value, 
                year: parseInt($year.value),
                genres: selectedGenres,
                staff: []
            };
    
            cards.forEach(function (card) {
                const name = card.querySelector('input[type="text"]').value;
                const isDirector = card.querySelector('.is-director').checked;
                const isProtagonist = card.querySelector('.is-protagonist').checked;
        
                if (name) { 
                    newMovie.staff.push({
                        name: name,
                        director: isDirector,
                        protagonist: isProtagonist
                    });
                }
            });
        
            fetch(movieUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(newMovie)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();

            })
            .then(data => {
                console.log('Respuesta:', data);
                fillMovies();
                $sectionAddEditMovie.classList.add('hidden');
                $sectionMovies.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
            });

        }else{

            const updateMovie = {
                id: movieId,
                title: $title.value,
                country: $country.value,
                year: parseInt($year.value),

                url: $url.value, 
                genres: selectedGenres
            };

            console.log(updateMovie);

            fetch(movieUrl, {
                method: 'PUT',  
                headers: {
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify(updateMovie) 
            })
            .then(response => response.json())  
            .then(data => {
                console.log('Success:', data); 
                fillMovies();
            })
            .catch((error) => {
                console.error('Error:', error); 
            });

        }
        
    });

    $moviesContainer.addEventListener('click', function(event) {
        const targetId = event.target.id;
        
        if (targetId && (targetId.startsWith('D') || targetId.startsWith('V') || targetId.startsWith('E'))) {
            let movieId = targetId.replace(/^[DVE]/, '');  
            if (movieId) {
                movieId = parseInt(movieId);
                if (targetId.startsWith('D')) {
                    deleteMovie(movieId); 
                } else if (targetId.startsWith('V')) {
                    detailsMovie(movieId);  
                } else if (targetId.startsWith('E')) {
                    editMovie(movieId);  
                }
            }
        }
    });

    function detailsMovie(id)
    {   
        $containerDetails.innerHTML = '';
        $containerImg.innerHTML = '';

        fetch(movieUrl+`?id=${id}`)
        .then(response => {
            if (!response.ok) {
                console.log(movieUrl);
                throw new Error('Error en la solicitud');
            }
            return response.json(); 
        })
        .then(movie => {
            
            const details = `
            <p class="text-center" ><strong>Año:</strong> ${movie.year}</p>
            <p class="text-center" ><strong>País:</strong> ${movie.country}</p>
            <p class="text-center" ><strong>Géneros:</strong> ${movie.genres.join(', ')}</p>
            <h6 class="text-center" >Elenco:</h6>
            <div class="d-flex flex-wrap">
                ${movie.staff.map(staff => `
                    <div class="card p-1" style="width: 15rem;">
                        <div class="card-body">
                            <h6 class="card-title">${staff.name}</h6>
                            <p class="card-text">Director: ${staff.director === "1" ? 'Sí' : 'No'}</p>
                            <p class="card-text">Protagonista: ${staff.protagonist === "1" ? 'Sí' : 'No'}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
            `;
            document.getElementById('title-detail').innerText = movie.title;
            const valid = validUrl(movie.url);
            const imgStatic = './img/movie.png';
            const img = `<img src="${valid ? movie.url : imgStatic}" class="imgMovie" alt="...">`;
            $containerDetails.insertAdjacentHTML('beforeend', details);
            $containerImg.insertAdjacentHTML('beforeend', img);


        })
        .catch(error => {
            console.error('Hubo un problema con la solicitud:', error);
        });

        $sectionMovies.classList.add("hidden");
        $sectionAddEditMovie.classList.add("hidden");
        $sectionDetailsMovies.classList.remove('hidden');



    }
    function editMovie(id)
    {
        console.log(id);

        movieId = id;

        $btnAddPerson.disabled = true;

        fetch(movieUrl+`?id=${id}`)
        .then(response => {
            if (!response.ok) {
                console.log(movieUrl);
                throw new Error('Error en la solicitud');
            }
            return response.json(); 
        })
        .then(movie => {

            document.getElementById('title-add-edit').innerHTML = "Editar película";
            
            $title.value = movie.title;
            $title.disabled = true;
            $year.value = movie.year;
            $url.value = movie.url;
            $country.value = movie.country;


            const checkboxes = document.querySelectorAll('input[name="genre"]');
            checkboxes.forEach(checkbox => {
                if (movie.genres.includes(checkbox.value)) {
                    checkbox.checked = true;
                } else {
                    checkbox.checked = false;
                }
            });
            $peopleContainer.innerHTML = '';

            movie.staff.forEach(person =>{
                const personEntry = `
                <div class="card m-2" style="width: 18rem;">
                    <div class="card-body">
                        <input value="${person.name}" type="text" class="form-control mb-2" placeholder="Nombre" disabled>
                        <div class="form-check">
                            <input ${person.director != 0 ? 'checked' : ''} type="checkbox" class="form-check-input is-director" disabled>
                            <label class="form-check-label">Es Director</label>
                        </div>
                        <div class="form-check">
                            <input ${person.protagonist != 0 ? 'checked' : ''} type="checkbox" class="form-check-input is-protagonist" disabled>
                            <label class="form-check-label">Es Protagonista</label>
                        </div>
                        <button type="button" class="btn btn-danger removePerson" disabled>Eliminar</button>
                    </div>
                </div>
                `;

                $peopleContainer.insertAdjacentHTML('beforeend', personEntry);
            });

            $sectionMovies.classList.add("hidden");
            $sectionDetailsMovies.classList.add('hidden');
            $sectionAddEditMovie.classList.remove("hidden");

        })
        .catch(error => {
            console.error('Hubo un problema con la solicitud:', error);
        });

    }

    function deleteMovie(id)
    {
        console.log(id);
        fetch(movieUrl+`?id=${id}`, {
            method: 'DELETE', 
            headers: {
                'Content-Type': 'application/json', 
            },
        })
        .then(response => {
            if (response.ok) {
                console.log(`Película con ID ${id} eliminada con éxito.`);
                fillMovies();
            } else {
                console.error('Hubo un error al eliminar la película.');
            }
        })
        .catch(error => {
            console.error('Error de red:', error);
        });

    }

    function fillMovies()
    {
        $sectionMovies.classList.remove('hidden');
        $sectionAddEditMovie.classList.add("hidden");
        $sectionDetailsMovies.classList.add("hidden");
        $moviesContainer.innerHTML = '';
        let allMovies = '';
        fetch(movieUrl)
        .then(response => {
            if (!response.ok) {
                console.log(movieUrl);
                throw new Error('Error en la solicitud');
            }
            return response.json(); 
        })
        .then(movies => {
            movies.forEach(movie => {
                const valid = validUrl(movie.url);
                const img = './img/movie.png';
                allMovies += `<div class="card" style="width: 18rem;  display: flex;align-items: center;">
                    <img src=" ${valid ? movie.url : img} " class="card-img-top" alt="...">
                    <div class="card-body justify-content-center">
                        <h5 class="card-title  justify-content-center">${movie.title}</h5>
                        <p class="card-text"> ${movie.year} </p>
                    </div>
                    <div class="buttons-movie">
                        <button type="button" class="btn btn-warning"  style="color: white;"><i id="E${movie.id}" class="bi bi-pencil-square"></i></i></button>
                        <button type="button" class="btn btn-danger" ><i id="D${movie.id}" class="bi bi-trash3-fill"></i></button>
                        <button id="V${movie.id}" type="button" class="btn btn-primary" >Ver más <i class="bi bi-arrow-right-circle"></i></i>
                        </button>

                    </div>
                </div>`;
            });
            $moviesContainer.insertAdjacentHTML('beforeend', allMovies);

        })
        .catch(error => {
            console.error('Hubo un problema con la solicitud:', error);
        });
    }

    function fillCountries()
    {
        let optionsHtml = '';
        fetch(countryUrl)
        .then(response => {
            if (!response.ok) {
                console.log(movieUrl);
                throw new Error('Error en la solicitud');
            }
            return response.json(); 
        })
        .then(countries => {
            countries.forEach(country => {
                optionsHtml += `<option value="${country}">${country}</option>`;
            });
            document.getElementById("country").insertAdjacentHTML('beforeend', optionsHtml);

        })
        .catch(error => {
            console.error('Hubo un problema con la solicitud:', error);
        });
    }

    function fillGenres()
    {
        let optionsHtml = '';
        fetch(genreUrl)
        .then(response => {
            if (!response.ok) {
                console.log(movieUrl);
                throw new Error('Error en la solicitud');
            }
            return response.json(); 
        })
        .then(genres => {
            genres.forEach(genre => {
                optionsHtml += `<label>
                                    <input type="checkbox" name="genre" value="${genre}"> ${genre}
                                </label>`;
            });
            document.getElementById("genres").innerHTML += optionsHtml;

        })
        .catch(error => {
            console.error('Hubo un problema con la solicitud:', error);
        });

    }

    function validUrl(url) {
        try {
            new URL(url);  
            return true;   
        } catch (e) {
            return false;  
        }
    }

    

    
});




