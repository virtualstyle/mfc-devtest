**CODE TEST**

**We like you. Now, we want to see how you code.**

Below are a series of coding assignments you can complete over the next three days. The first one – the &quot;Main Assignment&quot; – is required. It should be fairly easy to complete in a weekend. The others are optional – your chance to show off a little bit and break apart from the pack. We&#39;d be really surprised if you finished all of this over three days, but you&#39;re welcome to try.

We&#39;re looking primarily at the code syntax and architecture. We want to see something that is clean, modular and readable. Keep it simple. Don&#39;t overcomplicate. And make it easy to follow. (Details like what you name your variables matter). We will be looking to see if you are leveraging Laravel&#39;s helper classes and how well you hold to MVC (Model - View - Controller) architecture. We&#39;ll be reading your comments to see if they are helpful. Also, everything should work.

Bottom line: Make sure you turn in your best work. We&#39;d rather see you do the main assignment well than have you rush to do the bonus work.

Thanks for your interest. Consider this code test an indication of how seriously we take our hiring process and how committed we are to our team. We owe it to our colleagues to only hire A players.



**MAIN ASSIGNMENT**

**Objective:** Build a Mini-CRM website using Laravel. Feel free to use generated sample text (i.e. your favorite version of Lorem Ipsum) and google images when needed.

**Requirements:**

1. Setup the project on a git based VC hosting platform like GitHub or Bitbucket.
2. Use Laravel to build a Mini-CRM site for managing companies and their employees.
3. Basic Laravel Auth: have the ability to log in as either an Administrator or Manager.
  1. Administrator Role Access:
    1. Can create/update/delete all companies.
    2. Can create/update/delete employees for any company.
    3. Can create/update/delete manager users.

- Including assigning company access to manager accounts.

1.
  1. Manager Role Access:
    1. Can only update companies they&#39;ve been given access to.
    2. Can create/update/delete employees for a company they&#39;ve been given access to.
2. Use database seeds to create the first admin and manager user accounts.
  1. Admin data: email [admin@site.com](mailto:admin@site.com) and password &quot;password&quot;.
  2. Manager data: email: [manager@site.com](mailto:manager@site.com) and password &quot;password&quot;.
3. CRUD functionality (Create / Read / Update / Delete) for two menu items: Companies and Employees.
4. Companies DB table consists of these fields: Name (required), email, logo (minimum 100×100), website.
5. Employees DB table consists of these fields: First name (required), last name (required), Company (foreign key to Companies), email, phone.
6. Use database migrations to create those schemas above.
7. Store companies logos in storage/app/public folder and make them accessible from public.
8. Use basic Laravel resource controllers with default methods – index, create, store etc.
9. Use Laravel&#39;s validation function, using Request classes.
10. Use Laravel&#39;s pagination for showing Companies/Employees list, 10 entries per page.
11. Use Laravel make:auth as default Bootstrap-based design theme, but remove ability to register.

**Bonus Tasks:**

1. Write up technical documentation for the Laravel tool you built (above) that could help a subsequent developer set up and build on the project.
2. Use docker and/or docker-compose for local deployment (include any special  instructions).
3. For any elements where it makes sense, implement them in a reusable manner.
4. Use Vue.js for handling admin menu asynchronous pagination.
5. Implement Unit, Functional and Acceptance tests using Codeception while following industry standard TDD methodologies.
6. Create a mail event to inform a manager that they&#39;ve been given access to a company.
7. Use Faker for adding mock company and employee data.
8. Allow the company/employee data to be accessed through a private web API.
9. Make the project multi-language (using resources/lang folder).
