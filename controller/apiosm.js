const OSM = require("osm-api");
// or
import * as OSM from "osm-api";

// you can call methods that don't require authentication
await OSM.getFeature("way", 23906749);

// Once you login, you can call methods that require authentication.
// See the section below about authentication.
await OSM.createChangesetComment(114733070, "Thanks for your edit!");