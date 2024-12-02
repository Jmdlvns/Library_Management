<script lang="ts">
  import { onMount } from 'svelte';

  interface OverdueBook {
    borrowing_id: number;
    title: string;
    borrower_name: string;
    borrow_date: string;
    due_date: string;
    days_overdue: number;
    fine: number;
  }

  let overdueBooks: OverdueBook[] = [];

  onMount(async () => {
    await fetchOverdueBooks();
  });

  async function fetchOverdueBooks() {
    const response = await fetch('localhost/Library-Management/backend/api/overdue_books.php');
    overdueBooks = await response.json();
  }

  $: totalFine = overdueBooks.reduce((sum, book) => sum + book.fine, 0);
</script>

<div class="bg-white shadow-md rounded-lg p-4 mb-4">
  <h2 class="text-2xl font-semibold mb-4">Overdue Books and Fines Report</h2>
  
  <table class="w-full mb-4">
    <thead>
      <tr>
        <th class="text-left">Book Title</th>
        <th class="text-left">Borrower</th>
        <th class="text-left">Due Date</th>
        <th class="text-left">Days Late</th>
        <th class="text-left">Fine</th>
      </tr>
    </thead>
    <tbody>
      {#each overdueBooks as book}
        <tr>
          <td>{book.title}</td>
          <td>{book.borrower_name}</td>
          <td>{book.due_date}</td>
          <td>{book.days_overdue}</td>
          <td>${book.fine.toFixed(2)}</td>
        </tr>
      {/each}
    </tbody>
  </table>

  <p class="font-semibold">Total Fine: ${totalFine.toFixed(2)}</p>
</div>

