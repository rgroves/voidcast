# Voidcast

The Voidcast journal gives me a way to cast my thoughts into the void, digitally.

This started as [a thought about building simply](https://bsky.app/profile/robertgroves.com/post/3lfyfduxyr22x), let's see where it leads.

To avoid over-optimizing on simplicity—[see this note on that](https://bsky.app/profile/robertgroves.com/post/3lfyifie2nk2i)—I'll outline a set of minimal requirements below.

# Requirements

## 2025-01-17 - Initial Minimal Requirements

- [x] I should be the only one able to create a journal entry.
- [x] I should be able to quickly add a text-only journal entry via a simple web-based UI
- [x] All journal entries are to be publically visible.

---

# Dev Notes

## Getting from 0 to deploy

I'm going to add a simple html file and get things configured so that when I commit to the repo it also deploys my code up to the web server. To meet this goal, I do need to figure out where I'm hosting this and how I deploy... all while keeping it simple.

### Hosting: A slight complication this way comes

My website [robertgroves.com](https://robertgroves.com) uses the Astro framework and is hosted up on Netlify. It isn't much right now, just has some Linkitty Links (my take on a linkinbio/linktree) up on it. I could have continued to use that to implement this Voidcast Journal, but that wouldn't have met [the challenge I laid out for myself](https://bsky.app/profile/robertgroves.com/post/3lfyfduxyr22x)—building simply from scratch. So I decided to just use a sub-domain, [void.robertgroves.com](https://void.robertgroves.com/), for this temporarily, instead of getting yet-another-domain-name™. To avoid getting another web hosting account I'm using a different shared hosting account I've had for a very long time that I just use for throwing various random acts of digital 💩 up on from time to time. I guess it could be considered cheating since this doesn't technically qualify as from scratch, but I'll make an exception here for two reasons:

1. I'm not using any pre-existing framework/structure that exists up on that shared hosting server; I've configured an NS and A record on Netlify so that [void.robertgroves.com](https://void.robertgroves.com/) hits a newly created sub-domain on the shared hosting server. This effectively gives me a clean slate to work on.
2. I'm making up the rules as I go and you can't stop me. 😉 (though I am trying to stay true to the spirit of the challenge)

### Deploying

Originally I thought to use a GitHub Action to deploy the code to the web server, but I've decided to go simpler for now, so I'm creating a small deploy script that just performs a secure copy of the files to the web host from my local machine. Pro: I don't have to evaluate or create and configure a GitHub Action that can securely perform the transfer. Con: I'll need discipline to remember to deploy and only after committing the code (so the deployed state is always represented under version control).

I just realized I'm on a new laptop that has never connected via ssh to the web server I'm using so a slight detour was taken to generate a new ssh key and configure it on the server. Ran an scp test just to be sure things were working as expected and deploying is now good to go.
