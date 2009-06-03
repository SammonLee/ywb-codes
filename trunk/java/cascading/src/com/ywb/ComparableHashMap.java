package com.ywb;

import java.util.HashMap;

@SuppressWarnings("unchecked")
public class ComparableHashMap<K,V> extends HashMap<K, V> implements Comparable {
	/**
	 * 
	 */
	private static final long serialVersionUID = 2769839945941671869L;

	@Override
	public int compareTo(Object o) {
		return this.size() - ((ComparableHashMap<K, V>)o).size();
	}
}
