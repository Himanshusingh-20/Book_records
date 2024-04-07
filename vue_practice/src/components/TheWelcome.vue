<template>
  <div>
    <h4>Add New Book</h4>
    <form @submit.prevent="addBook">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" v-model="newBook.title" required>
      </div>
      <div class="mb-3">
        <label for="author" class="form-label">Author</label>
        <input type="text" class="form-control" id="author" v-model="newBook.author" required>
      </div>
      <div class="mb-3">
        <label for="publishedYear" class="form-label">Published Year</label>
        <input type="number" class="form-control" id="publishedYear" v-model="newBook.published_year" required>
      </div>
      <div class="mb-3">
        <label for="genre" class="form-label">Genre</label>
        <input type="text" class="form-control" id="genre" v-model="newBook.genre" required>
      </div>
      <div class="mb-3">
        <label for="isbn" class="form-label">ISBN</label>
        <input type="text" class="form-control" id="isbn" v-model="newBook.isbn" required>
      </div>
      <div class="mb-3">
        <label for="publisher" class="form-label">Publisher</label>
        <input type="text" class="form-control" id="publisher" v-model="newBook.publisher" required>
      </div>
      <div class="mb-3">
        <label for="pages" class="form-label">Pages</label>
        <input type="number" class="form-control" id="pages" v-model="newBook.pages" required>
      </div>
      <div class="mb-3">
        <label for="language" class="form-label">Language</label>
        <input type="text" class="form-control" id="language" v-model="newBook.language" required>
      </div>
      <div class="mb-3">
        <label for="summary" class="form-label">Summary</label>
        <textarea class="form-control" id="summary" v-model="newBook.summary" required></textarea>
      </div>
      <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <input type="number" class="form-control" id="rating" v-model="newBook.rating" required>
      </div>
      <div class="mb-3">
        <label for="reviews" class="form-label">Reviews</label>
        <input type="number" class="form-control" id="reviews" v-model="newBook.reviews" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      newBook: {
        book_id: null,
        title: '',
        author: '',
        published_year: null,
        genre: '',
        isbn: '',
        publisher: '',
        pages: null,
        language: '',
        summary: '',
        rating: null,
        reviews: null
      }
    }
  },
  mounted() {
    // Calculate the next book_id when the component is mounted
    this.calculateNextBookId();
  },
  methods: {
    async addBook() {
      try {
        // Add the new book with the calculated book_id
        const response = await axios.post('http://localhost/bank_api/api/index.php/books', this.newBook);
        console.log('Book added successfully:', response.data);
        // Emit an event to notify parent component about the addition
        this.$emit('book-added', response.data);
        // Clear the form fields after submission
        this.clearForm();
        // Calculate the next book_id for the next addition
        this.calculateNextBookId();
      } catch (error) {
        console.error('Error adding book:', error);
      }
    },
    calculateNextBookId() {
      // Find the maximum book_id from the existing books and increment it by 1
      const maxBookId = Math.max(...this.books.map(book => book.book_id), 0);
      this.newBook.book_id = maxBookId + 1;
    },
    clearForm() {
      // Reset form fields
      this.newBook = {
        book_id: null,
        title: '',
        author: '',
        published_year: null,
        genre: '',
        isbn: '',
        publisher: '',
        pages: null,
        language: '',
        summary: '',
        rating: null,
        reviews: null
      };
    }
  }
}
</script>