# TODO
## First Beta
- ~~Make router handle login~~
- ~~Deal with editor popup pos~~
- ~~Update article error pages~~
- ~~Style description box in editor~~
- ~~Disallow illegal article ids~~
- ~~Show active drafts on the write page~~
- ~~Style disabled menu buttons in editor and improve js~~
- ~~Add the edit button to articles and style it~~
- ~~Use UNIX timestamps for dates~~
- ~~Link to the manual in editor~~
- ~~Update texts across the entire website~~
- ~~Create beta fork~~
  - ~~Delete all dev files~~
  - ~~Change password~~

## First release
- ~~Fix `?continue`~~
- ~~Style log out button~~
- ~~Rename all "temp" to "draft"~~
- ~~Version selection for article page~~
- ~~Ability to delete draft~~
- Get feedback from community!
- Ask community for password
- Optimize for mobile
- Caching?
- Add stuff to editor
  - Start popup
  - Dropdown
- Add stuff to compiler/editor
  - ~~Tag nesting~~
  - ~~Bold, Italic, Underline~~ **Strikethrough**
  - Images
  - Links
  - Lists
  - Person says
  - Tables

## Coming releases
- Syntax highlighting in editor
- Make seperate page for article versions

# Hidden things
(Hopefully) all easter eggs are also marked with `Here's an easter egg…` in a comment

- "unkompilziert" in `index.php html body main section#features p:nth-of-type(2)` and `index.js $("#pilz").on("mouseover", …).on("mouseout", …)`

# Infos
- The project was initated by Mattis
- Emil agreed to develop the website
- We decided to host it on Mattis’ server
- Mattis was made the manager of the project on *February 1, 2023*
- Content is created by the entire class
- The website is maintained by Emil and Robin

# DevLog
## November 18, 2022
- Creating website on [000webhost](https://000webhost.com/)
- Implementing verification page from [GitHub](https://github.com/henry7720/Verification-Page/)
- Writing some css for verification page
- Adding color palette

## February 3, 2023
- Moving the website to [Replit](https://replit.com/)

## February 4, 2023
- Beginning development
- Removing color palette, adding new
- Refining verification page

## February 5, 2023
- Finishing verification page
- Starting to style nav

## February 6, 2023
- Finishing nav
- Starting to design home page

## March 13, 2023
I haven't worked on this for quite a while and I noticed that this website is not as good as I'd want it to be, so I decided to start over.
- Deleting everything I made until now
- Starting to style the new login page

## March 14, 2023
Today, I made a whole lot of progress! I think the project turns out pretty good. Also, I really like having this little text before the progress, so I'm gonna continue writing it.
- Finishing the login page
- Re-implementing the [verification page](https://github.com/henry7720/Verification-Page/)
- Starting with the article design

## March 15, 2023
Okay, I am quite productive currently! I think the article design turns out great. I'm progressing faster than expected, so it won't be long until I'm done!
- Refining article design
- Implementing tables

## March 16, 2023
I'm currently trying to implement a video player and what can I say – It just doesn't wanna work! But I made some progress and had fun writing the php to automate the article implementation.
- Creating a *fairly buggy* video player
- Starting to implement article management

## March 18, 2023
It's so much fun to experiment with fonts, their sizes, margins and stuff until you're entirely happy! So that's what I did today.
- Finishing article design
- Progressing on automatic article implementation
- Commenting out the video player, but keeping the code

## March 20, 2023
Today I progressed a little on the article page, but it wasn't much

## March 22, 2023
Today we decided to prepare everything for an alpha version on Friday, March 24th. It will be a private alpha, so only Mattis will be able to view it.

## March 24, 2023
Today was the most productive day I've ever had with this project. I coded all the error pages, an entire topic selection and I finally finished the content management!
- Tweaking some CSS
- Coding the Error pages
  - Topic not found
  - Topic not set
  - Version not found
- Coding the entire topic selector
- Finishing the CMS (for now)
- Releasing the first alpha version!

## April 18, 2023
I coded a bit during the day and in the afternoon I made quite some progress. It was fun to make something new and not only modify stuff.
- Adding the create page
  - Infos about the editor
  - Options to edit and create new

## April 19, 2023
I spent a lot of time in this today but made suprisingly little prgress. It was relaxing nonetheless. Well, except for the transitions…
- Fixing and refining some stuff

## April 20, 2023
Today I started woring on the editor. However, it still is just a styled textbox and a useless button. I'm gonna change that tomorrow
- Creating editor page

## April 21, 2023
I started working on AJAX requests to make the editor work. A new experience to me, and it is really cool.
- Starting to make the editor functional
- Moving from POST arguments to localStorage for the article id

## April 22, 2023
Today was fun. I successfully used AJAX to make a "save to server" and "load from server" function for the editor.
- Coding load and save functions for editor
- Adding "disallowed character" and "already exists" to the create page

## April 23, 2023
Pretty productive today, I'd say. I changed the nav, started the work on styling the editor and it was quite fun.
- Modifying nav to look better
- Adding toolbar to editor
- Fixing some stuff

## April 24, 2023
Didn't do much today, but I'm planning to roll out a beta version soon
- Adding an "Add an element" dropdown to the editor

## April 25, 2023
- Adding search bar
- Coding working AJAX function for search

## April 27, 2023
Today we fixed a lot of stuff and worked on completing the creation system.
- Perfecting the article id form of create article
- Adding content for edit article
  - Search bar
  - Article selection

## May 14, 2023
Oh wow, I've not worked on this for a **while**! However, I did make some progress today. Won't be long until the beta can release!
- Creating draft article system

## May 17, 2023
Today, we coded a little together and made quite some progress on the release function
- Including compiler
- Editor now doesn't display text field when no article is chosen
- Drafts with ampersands and other illegal symbols will now save correctly
- txt files can be partly converted to json articles

## May 18, 2023
I think most of the release stuff works now, we just need to connect everything and fill in some gaps.
- Breaking Save & Load
- Fixing Save & Load
- When you edit an existing article, it loads its current content into the editor
  - Using decompilation
- Title, subtitle and description are now stored in another file
- Announcing beta for this/next week

## May 19, 2023
A LOT of bug fixing today, but it was fun. With the new router script, we can now customize what users can access and log all requests!
- Adding router script
  - Fixing requests with GET attributes (they were kinda broken at first)
  - Blocking README, compile and request log as well as the article folder
  - php files (except for the router) are now in a php folder
- Creating request log
  - Logs time, IP, request URI and response code for every request

## May 23, 2023
We changed the editor today and started working on the publish function
- Adding publish button (fa-up-from-arrow doesn't work)
- Making spin into a seperate function
- Using Download/Upload/Publish now blocks those functions from being used
- Making popups, but not implementing them

## May 26, 2023
- Popups are now fully implemented
- Release works now, but infos is missing, so the article search is broken now
- Adding infos to release (except for version)

## May 27, 2023
Coding was **so much** fun today. Now, the publishing works flawlessly and we're so close to the beta!
- Switching from time-based version files to version-based ones
- Fixing release and search
- You're redirected to the Article page after publishing
- You can see the new version before publishing
- Changing publish popup

## May 28, 2023
- Blockqoutes now have a start and end tag
- All articles have an edit button, but it still needs to be styled
- Finishing publish popup

## May 29, 2023
Today, I modified the router so it serves files only if the user is logged or the file is needed for the login page. Huge improvement!
- Replacing login background with AI-generated, non-stolen one
- The login is now handled by the router instead of the individual pages

## May 31, 2023
- Adding a little paragrah about drafts

## June 2, 2023
- Styling description box in editor
- Updating write page
  - Improving drafts info
  - Adding disclaimer for non-existent features

## June 3, 2023
Today we coded an awesome draft overview and a **lot** more!
- There's a new page that shows all drafts
- All `<p class="subtitle">` are now `<subtitle>`
- Adding a list of features to index
- Modifying write page
  - More and better text
  - Links to drafts and explanation

## June 4, 2023
Today we coded some improvements to make it easier to use
- Added manual to the website
- Clarifying texts on write page

## June 5, 2023
Not much time today, we added nothing more than just a little feature
- New button in editor 
that opens manual

## June 6, 2023
- Heavily updated the manual
  - Moved "Über Entwürfe" here
  - Added "Toolbar" section
  - Sections are now closed and expandable
  - Added the option to open the manual with a section open

## June 7, 2023
The beta is done! It's finally ready to be seen by the others. With just some improvements of the texts, it was really much to do but a little annoying.
- Updating index
  - Styling feature overview
- Creating beta clone
- Changing password in beta
- Releasing the beta

## June 10, 2023
Today I tried to implement caching to reduce load times, but… it just *doesn't* want to work. We'll have much better speeds on Mattis' server anyway, so I'll keep it like this for now
- Testing caching, doesn't work
  - Future consideration

## June 11, 2023
I never really noticed, but since we have the router, `?continue` doesn't work anymore. So, I re-implemtented that and fixed a stupid mistake in the GET attribute handling.
- When trying to access a logged-in-only page and then logging in, you're redirected to that page
- You can now access anything if you put the encoded password into `?key`
  - The password can be encoded with `/api/encode-key.php`
- All references to "temp" are now renamed to "draft"
- Drafts can now be deleted in drafts.php if they were last edited 3 days ago or earlier
- The article version can now be selected via a dropdown
- The author info looks better

## June 12, 2023
- Implementing upgraded pexer

## June 14, 2023
Emil coerced Robin into entirely rewriting the interpreter with him. Currently it's still a pexer (a combined lexer/parser) and we also want nestable tags, so that seems like a good idea.
- Writing the lexer
- Started to work on parser

## June 16, 2023
- Finished the interpreter
- Modified interpreter to fit new article structure
- Modified decompiler to fit new article structure
- Adding loading animation to the logout button and moving logout.php into /api
- Creating snapshot `r1.0snap01`