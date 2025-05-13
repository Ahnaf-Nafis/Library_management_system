const books = [
  {
    id: 1,
    title: "Don Quixote",
    author: "Miguel de Cervantes",
    price: 18.99,
    rating: 5,
    image: "https://upload.wikimedia.org/wikipedia/en/6/6f/Don_Quixote_1605.jpg"
  },
  {
    id: 2,
    title: "A Tale of Two Cities",
    author: "Charles Dickens",
    price: 14.5,
    rating: 5,
    image: "https://upload.wikimedia.org/wikipedia/commons/3/3e/Tale_of_two_cities.jpg"
  },
  {
    id: 3,
    title: "The Lord of the Rings",
    author: "J.R.R. Tolkien",
    price: 22.5,
    rating: 5,
    image: "https://m.media-amazon.com/images/I/91dSMhdIzTL.jpg"
  },
  {
    id: 4,
    title: "The Little Prince",
    author: "Antoine de Saint-Exupéry",
    price: 12.75,
    rating: 5,
    image: "https://m.media-amazon.com/images/I/71Zn6EQ1DLL.jpg"
  },
  {
    id: 5,
    title: "Harry Potter and the Sorcerer's Stone",
    author: "J.K. Rowling",
    price: 16.95,
    rating: 5,
    image: "https://m.media-amazon.com/images/I/81iqZ2HHD-L.jpg"
  },
  {
    id: 6,
    title: "And Then There Were None",
    author: "Agatha Christie",
    price: 11.25,
    rating: 4,
    image: "https://m.media-amazon.com/images/I/91zbi9M+mKL.jpg"
  },
  // Add other books (same structure as above)
];

const bookList = document.getElementById("book-list");

books.forEach(book => {
  const div = document.createElement("div");
  div.className = "book";
  div.setAttribute("data-aos", "fade-up");

  div.innerHTML = `
    <img src="${book.image}" alt="${book.title}" />
    <h3>${book.title}</h3>
    <p>${book.author}</p>
    <p>$${book.price}</p>
    <div class="stars">${"★".repeat(book.rating)}${"☆".repeat(5 - book.rating)}</div>
    <button onclick="addToCart(${book.id})">Add to Cart</button>
  `;

  bookList.appendChild(div);
});

function addToCart(bookId) {
  const book = books.find(b => b.id === bookId);
  let cart = [];

  try {
    cart = JSON.parse(localStorage.getItem("cart")) || [];
  } catch {
    cart = [];
  }

  const exists = cart.find(item => item.id === bookId);
  if (exists) {
    exists.quantity += 1;
  } else {
    cart.push({ ...book, quantity: 1 });
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

function updateCartCount() {
  let cart = [];

  try {
    cart = JSON.parse(localStorage.getItem("cart")) || [];
  } catch {
    cart = [];
  }

  let totalItems = 0;
  if (Array.isArray(cart)) {
    totalItems = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
  }

  const countElement = document.getElementById("cart-count");
  if (countElement) {
    countElement.textContent = totalItems;
  }
}

updateCartCount();
