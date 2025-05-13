const books = [
  {
    title: "Harry Potter and the Goblet of Fire",
    author: "J.K. Rowling",
    price: 9.99,
    rating: 4.8,
    stock: 10,
    description: "Harry faces dangerous magical challenges in his fourth year at Hogwarts.",
    image: "https://covers.openlibrary.org/b/id/7884866-L.jpg",
  },
  {
    title: "Pride and Prejudice",
    author: "Jane Austen",
    price: 9.99,
    rating: 4.5,
    stock: 7,
    description: "A classic romantic novel about Elizabeth Bennet and Mr. Darcy.",
    image: "https://covers.openlibrary.org/b/id/10256361-L.jpg",
  },
  {
    title: "1984",
    author: "George Orwell",
    price: 8.99,
    rating: 4.6,
    stock: 15,
    description: "A dystopian tale of surveillance, control, and rebellion.",
    image: "https://covers.openlibrary.org/b/id/153541-L.jpg",
  },
  {
    title: "The Great Gatsby",
    author: "F. Scott Fitzgerald",
    price: 10.49,
    rating: 4.3,
    stock: 12,
    description: "A story of wealth, love, and the American Dream in the 1920s.",
    image: "https://covers.openlibrary.org/b/id/7222246-L.jpg",
  },
  {
    title: "To Kill a Mockingbird",
    author: "Harper Lee",
    price: 11.99,
    rating: 4.7,
    stock: 8,
    description: "A young girl's perspective on racial injustice in the American South.",
    image: "https://covers.openlibrary.org/b/id/9871864-L.jpg",
  },
  {
    title: "Jane Eyre",
    author: "Charlotte Brontë",
    price: 9.59,
    rating: 4.4,
    stock: 9,
    description: "A gothic novel about love, morality, and independence.",
    image: "https://covers.openlibrary.org/b/id/8228691-L.jpg",
  },
  {
    title: "The Catcher in the Rye",
    author: "J.D. Salinger",
    price: 10.49,
    rating: 4.1,
    stock: 11,
    description: "A teenage boy’s struggle with identity, alienation, and loss.",
    image: "https://covers.openlibrary.org/b/id/8231856-L.jpg",
  },
  {
    title: "Wuthering Heights",
    author: "Emily Brontë",
    price: 9.79,
    rating: 4.2,
    stock: 6,
    description: "A dark tale of passion and revenge on the Yorkshire moors.",
    image: "https://covers.openlibrary.org/b/id/8235082-L.jpg",
  },
  {
    title: "Little Women",
    author: "Louisa May Alcott",
    price: 8.59,
    rating: 4.6,
    stock: 10,
    description: "The lives of four sisters growing up during the Civil War era.",
    image: "https://covers.openlibrary.org/b/id/10958379-L.jpg",
  },
  {
    title: "Frankenstein",
    author: "Mary Shelley",
    price: 7.49,
    rating: 4.0,
    stock: 14,
    description: "A scientist creates life and faces the consequences of playing God.",
    image: "https://covers.openlibrary.org/b/id/8228781-L.jpg",
  },
  {
    title: "Dracula",
    author: "Bram Stoker",
    price: 9.29,
    rating: 4.1,
    stock: 8,
    description: "A horror classic about the infamous vampire Count Dracula.",
    image: "https://covers.openlibrary.org/b/id/8231994-L.jpg",
  },
  {
    title: "The Odyssey",
    author: "Homer",
    price: 11.49,
    rating: 4.4,
    stock: 10,
    description: "Odysseus’s journey home after the fall of Troy.",
    image: "https://covers.openlibrary.org/b/id/10508158-L.jpg",
  },
  {
    title: "Crime and Punishment",
    author: "Fyodor Dostoevsky",
    price: 13.49,
    rating: 4.5,
    stock: 5,
    description: "A psychological drama about guilt, redemption, and morality.",
    image: "https://covers.openlibrary.org/b/id/11153217-L.jpg",
  },
  {
    title: "Anna Karenina",
    author: "Leo Tolstoy",
    price: 12.59,
    rating: 4.6,
    stock: 6,
    description: "A tragic romance set in Russian high society.",
    image: "https://covers.openlibrary.org/b/id/8319252-L.jpg",
  },
  {
    title: "The Brothers Karamazov",
    author: "Fyodor Dostoevsky",
    price: 14.29,
    rating: 4.7,
    stock: 4,
    description: "A philosophical and emotional story about family, faith, and justice.",
    image: "https://covers.openlibrary.org/b/id/10508354-L.jpg",
  },
  {
    title: "War and Peace",
    author: "Leo Tolstoy",
    price: 15.99,
    rating: 4.6,
    stock: 3,
    description: "An epic tale of history, love, and war in Russia.",
    image: "https://covers.openlibrary.org/b/id/10023178-L.jpg",
  },
  {
    title: "Great Expectations",
    author: "Charles Dickens",
    price: 9.99,
    rating: 4.3,
    stock: 9,
    description: "A coming-of-age story filled with mystery and drama.",
    image: "https://covers.openlibrary.org/b/id/7222248-L.jpg",
  },
  {
    title: "Brave New World",
    author: "Aldous Huxley",
    price: 8.99,
    rating: 4.4,
    stock: 7,
    description: "A futuristic society engineered for control and conformity.",
    image: "https://covers.openlibrary.org/b/id/8373936-L.jpg",
  },
  {
    title: "Les Misérables",
    author: "Victor Hugo",
    price: 12.99,
    rating: 4.5,
    stock: 6,
    description: "Redemption and revolution in post-revolutionary France.",
    image: "https://covers.openlibrary.org/b/id/8279253-L.jpg",
  },
  {
    title: "The Count of Monte Cristo",
    author: "Alexandre Dumas",
    price: 13.99,
    rating: 4.7,
    stock: 10,
    description: "A gripping tale of betrayal, revenge, and redemption.",
    image: "https://covers.openlibrary.org/b/id/8279262-L.jpg",
  }
];

// (Rest of the script stays unchanged)


function renderBooks() {
  const bookList = document.getElementById("book-list");
  bookList.innerHTML = "";

  books.forEach((book, index) => {
    const bookDiv = document.createElement("div");
    bookDiv.className = "book";
    bookDiv.innerHTML = `
      <img src="${book.image}" alt="${book.title}" onclick="showDetails(${index})" />
      <h3 onclick="showDetails(${index})">${book.title}</h3>
      <p>${book.author}</p>
      <p class="stars">${"★".repeat(Math.round(book.rating))}</p>
      <p>$${book.price.toFixed(2)}</p>
      <button onclick="addToCart(${index})">Add to Cart</button>
    `;
    bookList.appendChild(bookDiv);
  });
}

function showDetails(index) {
  const book = books[index];

  let existing = document.getElementById("book-popup");
  if (existing) existing.remove();

  const popup = document.createElement("div");
  popup.id = "book-popup";
  popup.innerHTML = `
    <div class="popup-content">
      <button class="close-btn" onclick="document.getElementById('book-popup').remove()">×</button>
      <h2>${book.title}</h2>
      <p><strong>Author:</strong> ${book.author}</p>
      <p><strong>Description:</strong> ${book.description}</p>
      <p><strong>Stock:</strong> ${book.stock} copies left</p>
      <p><strong>Rating:</strong> ${book.rating.toFixed(1)} / 5</p>
    </div>
  `;
  document.body.appendChild(popup);
}

function addToCart(index) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const book = books[index];
  const existingIndex = cart.findIndex((item) => item.title === book.title);
  if (existingIndex !== -1) {
    cart[existingIndex].quantity += 1;
  } else {
    cart.push({ ...book, quantity: 1 });
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const count = cart.reduce((total, item) => total + item.quantity, 0);
  document.getElementById("cart-count").textContent = count;
}

function collapseHeroSection() {
  const hero = document.querySelector(".hero");
  if (hero) {
    setTimeout(() => {
      hero.style.transition = "opacity 1s ease, height 1s ease, margin 1s ease, padding 1s ease";
      hero.style.opacity = "0";
      hero.style.height = "0px";
      hero.style.padding = "0px";
      hero.style.margin = "0px";
      setTimeout(() => hero.style.display = "none", 1000);
    }, 3000);
  }
}

function setupFlashSale() {
  const saleSection = document.getElementById("sale-section");
  const book = books[Math.floor(Math.random() * books.length)];
  const discount = Math.floor(Math.random() * 16) + 5;
  const discountedPrice = (book.price * (1 - discount / 100)).toFixed(2);
  let secondsLeft = 120;

  const saleBox = document.createElement("div");
  saleBox.className = "special-sale";
  saleBox.innerHTML = `
    <h2>🔥 Flash Sale: ${discount}% OFF! (<span id="sale-timer">${formatTime(secondsLeft)}</span>)</h2>
    <img src="${book.image}" alt="${book.title}" />
    <p><strong>${book.title}</strong></p>
    <p>${book.author}</p>
    <p><del>$${book.price.toFixed(2)}</del> <span style="color:red;">$${discountedPrice}</span></p>
    <p class="stars">${"★".repeat(Math.round(book.rating))}</p>
    <button onclick='addToCartFromSale(${JSON.stringify(book)}, ${discount})'>Add to Cart</button>
  `;
  saleSection.appendChild(saleBox);

  const interval = setInterval(() => {
    secondsLeft--;
    const timer = document.getElementById("sale-timer");
    if (timer) {
      timer.textContent = formatTime(secondsLeft);
    }
    if (secondsLeft <= 0) {
      clearInterval(interval);
      saleSection.innerHTML = "";
    }
  }, 1000);
}

function formatTime(seconds) {
  const min = Math.floor(seconds / 60);
  const sec = seconds % 60;
  return `${min}:${sec.toString().padStart(2, "0")}`;
}

function addToCartFromSale(book, discount) {
  const discountedPrice = book.price * (1 - discount / 100);
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const existingIndex = cart.findIndex((item) => item.title === book.title);
  if (existingIndex !== -1) {
    cart[existingIndex].quantity += 1;
  } else {
    cart.push({ ...book, quantity: 1, price: discountedPrice });
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

document.addEventListener("DOMContentLoaded", () => {
  renderBooks();
  updateCartCount();
  collapseHeroSection();
  setupFlashSale();
});
