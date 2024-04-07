<template>
    <div>
        <h4>{{ selectedBookId ? 'Update Book' : 'Add New Book' }}</h4>

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
        <button @click="saveBook" class="btn btn-success">{{ selectedBookId ? 'Update' : 'Add' }}</button>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        selectedBookId: Number // Prop to receive the selected book ID
    },
    data() {
        return {
            books: [],
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
        };
    },
    async mounted() {
       
        if (this.selectedBookId) {
            // Fetch book details if selectedBookId is provided
            await this.fetchBookDetails(this.selectedBookId);
        }
        console.log("selected id ",this.selectedBookId);
    //    await this.fetchBooks();
        
       
    },
    methods: {
        // async fetchBooks() {
        //     try {
        //         // Fetch book data from the API endpoint
        //         const response = await axios.get('http://localhost/bank_api/api/index.php/books');
        //         console.log("response=>", response)
        //         this.books = response.data; // Assign fetched book data to the 'books' array
      

        //     } catch (error) {
        //         console.error('Error fetching books:', error);
        //     }
        // },
        async fetchBookDetails(bookId) {
            try {
                const response = await axios.get(`http://localhost/bank_api/api/index.php/books/${bookId}`);
                this.newBook = response.data; // Populate form fields with fetched book details
              
            } catch (error) {
                console.error('Error fetching book details:', error);
            }
        },
        async saveBook() {
            let bookId = this.selectedBookId && !isNaN(this.selectedBookId) ? parseInt(this.selectedBookId) : 0;
            console.log("savebook PS: ", this.selectedBookId);
            try {
                if (bookId > 0) {
                    // Update existing book
                    const response = await axios.put(`http://localhost/bank_api/api/index.php/books/${bookId}`, this.newBook);

                    // Log the updated book and emit an event to notify parent components
                    console.log('Book updated successfully', response.data);
                    this.$emit('book-updated');

                    // Fetch updated book details if necessary
                    this.fetchBookDetails();
                } else {
                    // Add new book
                    
                    const latestBookResponse = await axios.get('http://localhost/bank_api/api/index.php/books');
                    const latestBookId = latestBookResponse.data.reduce((maxId, book) => Math.max(maxId, book.book_id), 0);

                    // Increment the latest book ID to get the new book ID
                    this.newBook.book_id = latestBookId + 1;
                    this.newBook.bookId = latestBookId + 1;

                    // Send the POST request to add the new book
                    await axios.post('http://localhost/bank_api/api/index.php/books', this.newBook);
                    console.log('Book added successfully');

                    // Emit the 'book-added' event to notify parent components
                    this.$emit('book-added');
                }
                // Clear form fields after submission
                this.clearForm();
            } catch (error) {
                console.error('Error saving book:', error);
            }
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
};
</script>
