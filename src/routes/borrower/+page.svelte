<script lang="ts">
    import { onMount } from 'svelte';
    import BookCard from '$lib/components/BookCard.svelte';
    import BorrowBook from '$lib/components/BorrowBook.svelte';
    import ReturnBook from '$lib/components/ReturnBook.svelte';
  
    interface Book {
      id: number;
      title: string;
      author: string;
      isbn: string;
      genre: string;
      available_copies: number;
    }
  
    interface Borrowing {
      id: number;
      book_id: number;
      user_id: number;
      book_title: string;
      user_name: string;
      borrow_date: string;
      due_date: string;
      return_date: string | null;
      fine: number;
    }
  
    let availableBooks: Book[] = [];
    let borrowedBooks: Borrowing[] = [];
    let currentUserId: number;
  
    onMount(async () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        const user = JSON.parse(storedUser);
        currentUserId = user.id;
      }
      await fetchAvailableBooks();
      await fetchBorrowedBooks();
    });
  
    async function fetchAvailableBooks() {
      const response = await fetch('localhost/Library-Management/backend/api/books.php');
      const allBooks = await response.json();
      availableBooks = allBooks.filter((book: Book) => book.available_copies > 0);
    }
  
    async function fetchBorrowedBooks() {
      const response = await fetch('localhost/Library-Management/backend/api/borrowings.php');
      const allBorrowings = await response.json();
      borrowedBooks = allBorrowings.filter((borrowing: Borrowing) => borrowing.user_id === currentUserId && !borrowing.return_date);
    }
  
    async function borrowBook(book: Book) {
      const borrowDate = new Date().toISOString().split('T')[0];
      const dueDate = new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
  
      const response = await fetch('localhost/Library-Management/backend/api/borrowings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          book_id: book.id,
          user_id: currentUserId,
          borrow_date: borrowDate,
          due_date: dueDate
        })
      });
  
      const result = await response.json();
      if (result.message) {
        await fetchAvailableBooks();
        await fetchBorrowedBooks();
      } else {
        alert(result.error);
      }
    }
  
    async function returnBook(borrowing: Borrowing) {
      const returnDate = new Date().toISOString().split('T')[0];
      const response = await fetch('localhost/Library-Management/backend/api/borrowings.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id: borrowing.id,
          return_date: returnDate,
          fine: 0 // You may want to calculate the fine here or let the server handle it
        })
      });
  
      const result = await response.json();
      if (result.message) {
        await fetchAvailableBooks();
        await fetchBorrowedBooks();
      } else {
        alert(result.error);
      }
    }
  </script>
  
  <div>
    <h2 class="text-2xl font-semibold mb-4">Borrower Dashboard</h2>
  
    <div class="mb-8">
      <h3 class="text-xl font-semibold mb-2">Available Books</h3>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {#each availableBooks as book (book.id)}
          <div>
            <BookCard {book} />
            <BorrowBook {book} on:borrow={() => borrowBook(book)} />
          </div>
        {/each}
      </div>
    </div>
  
    <div>
      <h3 class="text-xl font-semibold mb-2">Borrowed Books</h3>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {#each borrowedBooks as borrowing (borrowing.id)}
          <div>
            <BookCard book={{
              id: borrowing.book_id,
              title: borrowing.book_title,
              author: "N/A", // We don't have this information in the Borrowing interface
              isbn: "N/A", // We don't have this information in the Borrowing interface
              genre: "N/A", // We don't have this information in the Borrowing interface
              available_copies: 0 // This book is borrowed, so available copies is 0
            }} />
            <ReturnBook book={{
              id: borrowing.id,
              title: borrowing.book_title,
              borrow_date: borrowing.borrow_date,
              due_date: borrowing.due_date
            }} on:return={() => returnBook(borrowing)} />
          </div>
        {/each}
      </div>
    </div>
  </div>
  
  