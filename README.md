**I have abandoned this project since VIA changed their website and I no longer have access to the required data.**

Hosted at <https://viarail.live/>

This app leverages open data from VIA Rail to display information about arrivals and departures at particular stations as well as information about train consists.

If you wish to run this in your own environment, you will need to schedule the shell scripts `gather.sh` and `prune.sh` on your server's crontab.
They are set up to use S3 but you can handle the archives differently if you wish.

Additionally, if you want to run the *consist* feature of the app, you will need access to an ElasticSearch cluster.
There may be other ways to achieve this, but the problem I faced was that you have to periodically ping the `vehiculesLookup.csv` to make sure you get complete data for the day, but you end up with many incomplete files and duplicate entries for the same train, so ES provides a way to quickly sort through it and only look at the top results to compose a complete consist for each train.
