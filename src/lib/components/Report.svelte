<script>
     import FineReport from './FineReport.svelte';
   
     let borrowedBooks = [
       { id: 1, title: "The Great Gatsby", borrower: "John Doe", borrowDate: "2023-05-01", dueDate: "2023-05-15" },
       { id: 2, title: "To Kill a Mockingbird", borrower: "Jane Smith", borrowDate: "2023-05-03", dueDate: "2023-05-17" },
     ];
   
     let bookInventory = [
       { id: 1, title: "The Great Gatsby", totalCopies: 5, availableCopies: 3 },
       { id: 2, title: "To Kill a Mockingbird", totalCopies: 4, availableCopies: 2 },
       { id: 3, title: "1984", totalCopies: 3, availableCopies: 3 },
     ];
   </script>
   
   <div>
     <h2 class="text-2xl font-semibold mb-4">Reports</h2>
   
     <div class="mb-8">
       <h3 class="text-xl font-semibold mb-2">Borrowed Books Report</h3>
       <table class="w-full">
         <thead>
           <tr>
             <th class="text-left">Book Title</th>
             <th class="text-left">Borrower</th>
             <th class="text-left">Borrow Date</th>
             <th class="text-left">Due Date</th>
           </tr>
         </thead>
         <tbody>
           {#each borrowedBooks as book}
             <tr>
               <td>{book.title}</td>
               <td>{book.borrower}</td>
               <td>{book.borrowDate}</td>
               <td>{book.dueDate}</td>
             </tr>
           {/each}
         </tbody>
       </table>
     </div>
   
     <div class="mb-8">
       <h3 class="text-xl font-semibold mb-2">Book Inventory Report</h3>
       <table class="w-full">
         <thead>
           <tr>
             <th class="text-left">Book Title</th>
             <th class="text-left">Total Copies</th>
             <th class="text-left">Available Copies</th>
           </tr>
         </thead>
         <tbody>
           {#each bookInventory as book}
             <tr>
               <td>{book.title}</td>
               <td>{book.totalCopies}</td>
               <td>{book.availableCopies}</td>
             </tr>
           {/each}
         </tbody>
       </table>
     </div>
   
     <FineReport />
   </div>