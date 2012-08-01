#Fascel
Fascel is a __f__lexible __a__nd __s__mart __c__hang__el__og generator.

##About
Changelogs are interesting for both the developers and the users to read, but they have a lot of down sides. It's usually a pain in the ass to generate them (although `git log` helps) and it's very static. Once your changelog is out, usually the content of it is carved in stone, without any dynamics. The big issue with this is that an user always views the changes relative to the latest release before that, but what if he hasn't checked out the changelog for more than one release? In that case he has to read multiple changelogs.

Here is where Fascel comes in. With Fascel, the user can choose the release he wants to view the changelog of and the release he wants that release compared against. That way, a changelog is generated which can span multiple releases, all in one view.
