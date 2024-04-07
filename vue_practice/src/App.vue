<template>
  <div>
    <header>
      <nav class="navbar bg-body-tertiary" data-bs-theme="dark">
        <div class="container">
          <a class="navbar-brand" href="#">
            <img src="./assets/logo.svg" alt="Vuelogo" width="30" height="24">
          </a>
        </div>
      </nav>
    </header>
    <div class="card">
      <div class="card-header">
        <h4>Books</h4>
        <button type="button" class="btn btn-primary" @click="toggleForm">ADD NEW BOOK</button>
      </div>
      <div class="container-fluid">
        <div v-if="showForm">
          <!-- Pass selectedBookId as a prop to the AddBookForm component -->
          <add-book-form :selectedBookId="selectedBookId" @form-cancelled="toggleForm" @book-added="handleBookAdded" />
        </div>
      </div>
      <div class="card-body">
        <table class="table table-success table-striped-columns">
          <thead class="table-dark">
            <tr>
              <td>Book ID</td>
              <td>Book Title</td>
              <td>Author</td>
              <td>Published Year</td>
              <td>Genre</td>
              <td>ISBN</td>
              <td>Publisher</td>
              <td>Pages</td>
              <td>Language</td>
              <td>Summary</td>
              <td>Rating</td>
              <td>Reviews</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="book in books" :key="book.book_id">
              <td>{{ book.book_id }}</td>
              <td>{{ book.title }}</td>
              <td>{{ book.author }}</td>
              <td>{{ book.published_year }}</td>
              <td>{{ book.genre }}</td>
              <td>{{ book.isbn }}</td>
              <td>{{ book.publisher }}</td>
              <td>{{ book.pages }}</td>
              <td>{{ book.language }}</td>
              <td>{{ book.summary }}</td>
              <td>{{ book.rating }}</td>
              <td>{{ book.reviews }}</td>
              <td>
                <button type="button" class="btn btn-info" @click="toggleForm(book.book_id)">Edit</button>
                <button type="button" class="btn btn-danger" @click="Deletedata(book.book_id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import AddBookForm from './components/books/addbooks.vue'; // Assuming your form component is in a separate file

export default {
  components: {
    AddBookForm
  },
  data() {
    return {
      showForm: false,
      books: [],
      selectedBookId: null // Initialize selectedBookId to null
    }
  },
  mounted() {
    this.fetchBooks();
  },
  methods: {
    async toggleForm(bookId = null) {
      if (bookId) {
        // Fetch book details if editing an existing book
        try {
          const response = await axios.get(`http://localhost/bank_api/api/index.php/books/${bookId}`);
          // Assign fetched book details to newBook object
          this.newBook = response.data; // Consider if you have a newBook data property declared somewhere
        } catch (error) {
          console.error('Error fetching book details:', error);
          return;
        }
      } else {
        // Reset form fields for adding a new book
        this.clearForm();
      }
      this.fetchBooks()
      // Ensure selectedBookId is null if bookId is null
      this.selectedBookId = bookId !== null ? parseInt(bookId) : null;
      // Show the form
      this.showForm = true;
    },
    async fetchBooks() {
      try {
        const response = await axios.get('http://localhost/bank_api/api/index.php/books');
        console.log("fetch ",response),
        this.books = response.data;
      } catch (error) {
        console.error('Error fetching books:', error);
      }
    },
    async Deletedata(bookId) {
      console.log("Deleting book with ID:", bookId); // Log the bookId to console
      try {
        await axios.delete(`http://localhost/bank_api/api/index.php/books/${bookId}`);
        // Remove the deleted book from the local list
        this.books = this.books.filter(book=> 
        book.book_id !== bookId);
      } catch (error) {
        console.error('Error deleting book:', error);
      }
    }
  }
}
</script>